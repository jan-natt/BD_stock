<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Models\Order;
use App\Models\Market;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TradeController extends Controller
{
    public function __construct()
    {
        // Apply middleware
        $this->middleware('auth');
        $this->middleware('verified')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource (Admin only).
     */
    public function index(Request $request)
    {
        // Only admins can view all trades
        $this->authorize('viewAny', Trade::class);

        $query = Trade::with(['buyOrder.user', 'sellOrder.user', 'market']);

        // Apply filters
        if ($request->has('market_id') && $request->market_id) {
            $query->where('market_id', $request->market_id);
        }

        if ($request->has('user_id') && $request->user_id) {
            $query->where(function($q) use ($request) {
                $q->whereHas('buyOrder', function($q) use ($request) {
                    $q->where('user_id', $request->user_id);
                })->orWhereHas('sellOrder', function($q) use ($request) {
                    $q->where('user_id', $request->user_id);
                });
            });
        }

        if ($request->has('start_date') && $request->start_date) {
            $query->where('trade_time', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('trade_time', '<=', $request->end_date . ' 23:59:59');
        }

        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('buyOrder.user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('sellOrder.user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('market', function($q) use ($search) {
                    $q->where('base_asset', 'like', "%{$search}%")
                      ->orWhere('quote_asset', 'like', "%{$search}%");
                });
            });
        }

        $trades = $query->latest('trade_time')->paginate(25);
        
        $users = User::select('id', 'name', 'email')->get();
        $markets = Market::select('id', 'base_asset', 'quote_asset')->get();

        return view('trades.index', compact('trades', 'users', 'markets'));
    }

    /**
     * Display the user's trades.
     */
    public function myTrades(Request $request)
    {
        $query = Trade::with(['buyOrder', 'sellOrder', 'market'])
            ->where(function($q) {
                $q->whereHas('buyOrder', function($q) {
                    $q->where('user_id', auth()->id());
                })->orWhereHas('sellOrder', function($q) {
                    $q->where('user_id', auth()->id());
                });
            });

        // Apply filters
        if ($request->has('market_id') && $request->market_id) {
            $query->where('market_id', $request->market_id);
        }

        if ($request->has('side') && $request->side) {
            if ($request->side === 'buy') {
                $query->whereHas('buyOrder', function($q) {
                    $q->where('user_id', auth()->id());
                });
            } elseif ($request->side === 'sell') {
                $query->whereHas('sellOrder', function($q) {
                    $q->where('user_id', auth()->id());
                });
            }
        }

        if ($request->has('start_date') && $request->start_date) {
            $query->where('trade_time', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('trade_time', '<=', $request->end_date . ' 23:59:59');
        }

        $trades = $query->latest('trade_time')->paginate(20);
        
        $markets = Market::where('status', true)->get();

        return view('trades.my-trades', compact('trades', 'markets'));
    }

    /**
     * Display the specified trade.
     */
    public function show(Trade $trade)
    {
        $this->authorize('view', $trade);

        $trade->load(['buyOrder.user', 'sellOrder.user', 'market']);

        return view('trades.show', compact('trade'));
    }

    /**
     * Execute a trade between two orders.
     */
    public function executeTrade(Request $request)
    {
        $this->authorize('execute', Trade::class);

        $validated = $request->validate([
            'buy_order_id' => 'required|exists:orders,id',
            'sell_order_id' => 'required|exists:orders,id',
            'price' => 'required|numeric|min:0.00000001',
            'quantity' => 'required|numeric|min:0.00000001',
        ]);

        try {
            DB::beginTransaction();

            $buyOrder = Order::findOrFail($validated['buy_order_id']);
            $sellOrder = Order::findOrFail($validated['sell_order_id']);

            // Validate orders can be matched
            $validationErrors = $this->validateTradeExecution($buyOrder, $sellOrder, $validated['price'], $validated['quantity']);
            if ($validationErrors) {
                throw new \Exception(implode(', ', $validationErrors));
            }

            // Execute the trade
            $trade = $this->createTrade($buyOrder, $sellOrder, $validated['price'], $validated['quantity']);

            // Update orders
            $this->updateOrdersAfterTrade($buyOrder, $sellOrder, $validated['quantity']);

            // Update user wallets
            $this->updateWalletsAfterTrade($buyOrder, $sellOrder, $trade);

            DB::commit();

            return response()->json([
                'success' => true,
                'trade' => $trade,
                'message' => 'Trade executed successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to execute trade: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Validate trade execution.
     */
    protected function validateTradeExecution(Order $buyOrder, Order $sellOrder, $price, $quantity): array
    {
        $errors = [];

        if ($buyOrder->order_type !== 'buy') {
            $errors[] = 'First order must be a buy order';
        }

        if ($sellOrder->order_type !== 'sell') {
            $errors[] = 'Second order must be a sell order';
        }

        if ($buyOrder->market_id !== $sellOrder->market_id) {
            $errors[] = 'Orders must be for the same market';
        }

        if ($buyOrder->status !== 'open' || $sellOrder->status !== 'open') {
            $errors[] = 'Both orders must be open';
        }

        // Price validation
        if ($buyOrder->order_kind === 'limit' && $price > $buyOrder->price) {
            $errors[] = 'Trade price exceeds buy order limit price';
        }

        if ($sellOrder->order_kind === 'limit' && $price < $sellOrder->price) {
            $errors[] = 'Trade price below sell order limit price';
        }

        // Quantity validation
        $buyRemaining = $buyOrder->quantity - $buyOrder->filled_quantity;
        $sellRemaining = $sellOrder->quantity - $sellOrder->filled_quantity;

        if ($quantity > $buyRemaining) {
            $errors[] = 'Quantity exceeds buy order remaining quantity';
        }

        if ($quantity > $sellRemaining) {
            $errors[] = 'Quantity exceeds sell order remaining quantity';
        }

        return $errors;
    }

    /**
     * Create trade record.
     */
    protected function createTrade(Order $buyOrder, Order $sellOrder, $price, $quantity): Trade
    {
        $market = $buyOrder->market;
        $feeRate = $market->fee_rate;
        $fee = ($quantity * $price * $feeRate) / 100;

        return Trade::create([
            'buy_order_id' => $buyOrder->id,
            'sell_order_id' => $sellOrder->id,
            'market_id' => $buyOrder->market_id,
            'price' => $price,
            'quantity' => $quantity,
            'fee' => $fee,
            'trade_time' => now(),
        ]);
    }

    /**
     * Update orders after trade.
     */
    protected function updateOrdersAfterTrade(Order $buyOrder, Order $sellOrder, $quantity): void
    {
        $buyOrder->increment('filled_quantity', $quantity);
        $sellOrder->increment('filled_quantity', $quantity);

        // Update order status
        if ($buyOrder->filled_quantity >= $buyOrder->quantity) {
            $buyOrder->update(['status' => 'filled']);
        } elseif ($buyOrder->filled_quantity > 0) {
            $buyOrder->update(['status' => 'partial']);
        }

        if ($sellOrder->filled_quantity >= $sellOrder->quantity) {
            $sellOrder->update(['status' => 'filled']);
        } elseif ($sellOrder->filled_quantity > 0) {
            $sellOrder->update(['status' => 'partial']);
        }
    }

    /**
     * Update wallets after trade.
     */
    protected function updateWalletsAfterTrade(Order $buyOrder, Order $sellOrder, Trade $trade): void
    {
        $market = $trade->market;
        $totalCost = $trade->quantity * $trade->price;

        // Update buyer's wallet (receive base asset, pay quote asset)
        $buyerBaseWallet = Wallet::firstOrCreate(
            ['user_id' => $buyOrder->user_id, 'currency' => $market->base_asset],
            ['balance' => 0, 'is_locked' => false]
        );
        
        $buyerQuoteWallet = Wallet::firstOrCreate(
            ['user_id' => $buyOrder->user_id, 'currency' => $market->quote_asset],
            ['balance' => 0, 'is_locked' => false]
        );

        $buyerBaseWallet->increment('balance', $trade->quantity);
        $buyerQuoteWallet->decrement('balance', $totalCost + $trade->fee);

        // Update seller's wallet (receive quote asset, pay base asset)
        $sellerBaseWallet = Wallet::firstOrCreate(
            ['user_id' => $sellOrder->user_id, 'currency' => $market->base_asset],
            ['balance' => 0, 'is_locked' => false]
        );
        
        $sellerQuoteWallet = Wallet::firstOrCreate(
            ['user_id' => $sellOrder->user_id, 'currency' => $market->quote_asset],
            ['balance' => 0, 'is_locked' => false]
        );

        $sellerBaseWallet->decrement('balance', $trade->quantity);
        $sellerQuoteWallet->increment('balance', $totalCost - $trade->fee);

        // Add fee to platform wallet (assuming platform has a wallet for each currency)
        $platformWallet = Wallet::firstOrCreate(
            ['user_id' => 1, 'currency' => $market->quote_asset], // user_id 1 is platform
            ['balance' => 0, 'is_locked' => false]
        );
        $platformWallet->increment('balance', $trade->fee * 2); // Fee from both sides
    }

    /**
     * Get trade history for a market.
     */
    public function marketHistory($marketId)
    {
        $market = Market::findOrFail($marketId);

        $trades = Trade::with(['buyOrder.user', 'sellOrder.user'])
            ->where('market_id', $marketId)
            ->orderBy('trade_time', 'desc')
            ->limit(100)
            ->get();

        return response()->json([
            'market' => $market->symbol,
            'trades' => $trades->map(function($trade) {
                return [
                    'id' => $trade->id,
                    'price' => (float)$trade->price,
                    'quantity' => (float)$trade->quantity,
                    'total' => (float)($trade->price * $trade->quantity),
                    'side' => 'buy', // This would be determined by the taker side
                    'trade_time' => $trade->trade_time,
                ];
            })
        ]);
    }

    /**
     * Get user's trade statistics.
     */
    public function userStatistics()
    {
        $userId = auth()->id();

        $stats = [
            'total_trades' => Trade::where(function($q) use ($userId) {
                $q->whereHas('buyOrder', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                })->orWhereHas('sellOrder', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            })->count(),

            'total_volume' => Trade::where(function($q) use ($userId) {
                $q->whereHas('buyOrder', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                })->orWhereHas('sellOrder', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            })->sum(DB::raw('price * quantity')),

            'buy_trades' => Trade::whereHas('buyOrder', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count(),

            'sell_trades' => Trade::whereHas('sellOrder', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count(),

            'total_fees' => Trade::where(function($q) use ($userId) {
                $q->whereHas('buyOrder', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                })->orWhereHas('sellOrder', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            })->sum('fee'),

            'by_market' => Trade::where(function($q) use ($userId) {
                $q->whereHas('buyOrder', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                })->orWhereHas('sellOrder', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            })
            ->join('markets', 'trades.market_id', '=', 'markets.id')
            ->selectRaw('markets.base_asset, markets.quote_asset, COUNT(*) as count, SUM(price * quantity) as volume')
            ->groupBy('markets.id', 'markets.base_asset', 'markets.quote_asset')
            ->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Export trades (Admin only).
     */
    public function export(Request $request)
    {
        $this->authorize('export', Trade::class);

        $validated = $request->validate([
            'format' => ['required', Rule::in(['csv', 'json'])],
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = Trade::with(['buyOrder.user', 'sellOrder.user', 'market']);

        if ($request->has('start_date') && $request->start_date) {
            $query->where('trade_time', '>=', $validated['start_date']);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('trade_time', '<=', $validated['end_date'] . ' 23:59:59');
        }

        $trades = $query->get();

        if ($validated['format'] === 'csv') {
            return $this->exportToCsv($trades);
        }

        return $this->exportToJson($trades);
    }

    /**
     * Export trades to CSV.
     */
    protected function exportToCsv($trades)
    {
        $fileName = 'trades-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($trades) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID', 'Market', 'Buyer', 'Seller', 'Price', 
                'Quantity', 'Total', 'Fee', 'Trade Time'
            ]);

            // Add data rows
            foreach ($trades as $trade) {
                fputcsv($file, [
                    $trade->id,
                    $trade->market->symbol,
                    $trade->buyOrder->user->name,
                    $trade->sellOrder->user->name,
                    $trade->price,
                    $trade->quantity,
                    $trade->price * $trade->quantity,
                    $trade->fee,
                    $trade->trade_time,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export trades to JSON.
     */
    protected function exportToJson($trades)
    {
        $fileName = 'trades-' . date('Y-m-d') . '.json';
        
        return response()->json($trades->toArray())
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Get platform trade statistics (Admin only).
     */
    public function platformStatistics()
    {
        $this->authorize('statistics', Trade::class);

        $stats = [
            'total_trades' => Trade::count(),
            'total_volume' => Trade::sum(DB::raw('price * quantity')),
            'total_fees' => Trade::sum('fee'),
            'daily_trades' => Trade::where('trade_time', '>=', now()->subDay())->count(),
            'daily_volume' => Trade::where('trade_time', '>=', now()->subDay())->sum(DB::raw('price * quantity')),
            'daily_fees' => Trade::where('trade_time', '>=', now()->subDay())->sum('fee'),
            'by_market' => Trade::join('markets', 'trades.market_id', '=', 'markets.id')
                ->selectRaw('markets.base_asset, markets.quote_asset, COUNT(*) as count, SUM(price * quantity) as volume, SUM(fee) as fees')
                ->groupBy('markets.id', 'markets.base_asset', 'markets.quote_asset')
                ->orderBy('volume', 'desc')
                ->get(),
            'by_hour' => Trade::selectRaw('HOUR(trade_time) as hour, COUNT(*) as count, SUM(price * quantity) as volume')
                ->where('trade_time', '>=', now()->subWeek())
                ->groupBy('hour')
                ->orderBy('hour')
                ->get(),
        ];

        return view('trades.statistics', compact('stats'));
    }
}