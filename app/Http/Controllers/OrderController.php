<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Market;
use App\Models\Wallet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
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
        // Only admins can view all orders
        $this->authorize('viewAny', Order::class);

        $query = Order::with(['user', 'market']);

        // Apply filters
        if ($request->has('order_type') && $request->order_type) {
            $query->where('order_type', $request->order_type);
        }

        if ($request->has('order_kind') && $request->order_kind) {
            $query->where('order_kind', $request->order_kind);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('market_id') && $request->market_id) {
            $query->where('market_id', $request->market_id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('market', function($q) use ($search) {
                    $q->where('base_asset', 'like', "%{$search}%")
                      ->orWhere('quote_asset', 'like', "%{$search}%");
                });
            });
        }

        $orders = $query->latest()->paginate(25);
        
        $users = User::select('id', 'name', 'email')->get();
        $markets = Market::select('id', 'base_asset', 'quote_asset')->get();
        
        $orderTypes = ['buy', 'sell'];
        $orderKinds = ['limit', 'market', 'stop-loss', 'take-profit'];
        $statuses = ['open', 'filled', 'partial', 'cancelled'];

        return view('orders.index', compact(
            'orders', 'users', 'markets', 'orderTypes', 'orderKinds', 'statuses'
        ));
    }

    /**
     * Display the user's orders.
     */
    public function myOrders(Request $request)
    {
        $query = Order::with('market')
            ->where('user_id', auth()->id());

        // Apply filters
        if ($request->has('order_type') && $request->order_type) {
            $query->where('order_type', $request->order_type);
        }

        if ($request->has('order_kind') && $request->order_kind) {
            $query->where('order_kind', $request->order_kind);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('market_id') && $request->market_id) {
            $query->where('market_id', $request->market_id);
        }

        $orders = $query->latest()->paginate(20);
        
        $markets = Market::where('status', true)->get();
        $orderTypes = ['buy', 'sell'];
        $orderKinds = ['limit', 'market', 'stop-loss', 'take-profit'];
        $statuses = ['open', 'filled', 'partial', 'cancelled'];

        return view('orders.my-orders', compact(
            'orders', 'markets', 'orderTypes', 'orderKinds', 'statuses'
        ));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create(Request $request)
    {
        $markets = Market::where('status', true)->get();
        $market = null;

        if ($request->has('market_id')) {
            $market = Market::find($request->market_id);
        }

        $orderTypes = ['buy', 'sell'];
        $orderKinds = ['limit', 'market', 'stop-loss', 'take-profit'];

        return view('orders.create', compact('markets', 'market', 'orderTypes', 'orderKinds'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'market_id' => 'required|exists:markets,id',
            'order_type' => ['required', Rule::in(['buy', 'sell'])],
            'order_kind' => ['required', Rule::in(['limit', 'market', 'stop-loss', 'take-profit'])],
            'price' => 'nullable|required_if:order_kind,limit,stop-loss,take-profit|numeric|min:0.00000001',
            'quantity' => 'required|numeric|min:0.00000001',
        ]);

        try {
            DB::beginTransaction();

            $market = Market::findOrFail($validated['market_id']);
            $user = auth()->user();

            // Validate order quantity against market limits
            if (!$market->isOrderSizeValid($validated['quantity'])) {
                throw new \Exception("Order quantity must be between {$market->min_order_size} and " . 
                    ($market->max_order_size > 0 ? $market->max_order_size : 'unlimited'));
            }

            // Check user balance for sell orders
            if ($validated['order_type'] === 'sell') {
                $baseWallet = Wallet::where('user_id', $user->id)
                    ->where('currency', $market->base_asset)
                    ->first();

                if (!$baseWallet || $baseWallet->balance < $validated['quantity']) {
                    throw new \Exception('Insufficient balance for sell order.');
                }

                // Lock the funds for the order
                $baseWallet->decrement('balance', $validated['quantity']);
            }

            // For buy orders, we'll check balance when the order gets filled

            $order = Order::create([
                'user_id' => $user->id,
                'market_id' => $validated['market_id'],
                'order_type' => $validated['order_type'],
                'order_kind' => $validated['order_kind'],
                'price' => $validated['price'] ?? null,
                'quantity' => $validated['quantity'],
                'filled_quantity' => 0,
                'status' => 'open',
            ]);

            // Process order matching (this would typically be done in a job)
            $this->processOrderMatching($order);

            DB::commit();

            return redirect()->route('orders.my-orders')
                ->with('success', 'Order created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create order: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['user', 'market', 'trades']);

        return view('orders.show', compact('order'));
    }

    /**
     * Cancel the specified order.
     */
    public function cancel(Order $order)
    {
        $this->authorize('cancel', $order);

        try {
            DB::beginTransaction();

            if ($order->status !== 'open') {
                throw new \Exception('Only open orders can be cancelled.');
            }

            // Return locked funds for sell orders
            if ($order->order_type === 'sell') {
                $market = $order->market;
                $wallet = Wallet::where('user_id', $order->user_id)
                    ->where('currency', $market->base_asset)
                    ->first();

                if ($wallet) {
                    $wallet->increment('balance', $order->quantity - $order->filled_quantity);
                }
            }

            $order->update(['status' => 'cancelled']);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Order cancelled successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to cancel order: ' . $e->getMessage());
        }
    }

    /**
     * Process order matching (simplified version).
     */
    protected function processOrderMatching(Order $order)
    {
        // This is a simplified version - in production, this would be handled
        // by a dedicated order matching engine or queue job
        
        $matchingOrders = Order::where('market_id', $order->market_id)
            ->where('order_type', $order->order_type === 'buy' ? 'sell' : 'buy')
            ->where('status', 'open')
            ->where(function($q) use ($order) {
                if ($order->order_kind === 'market') {
                    $q->whereIn('order_kind', ['limit', 'stop-loss', 'take-profit']);
                } else {
                    $q->where('price', '<=', $order->price);
                }
            })
            ->orderBy('price', $order->order_type === 'buy' ? 'asc' : 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($matchingOrders as $matchingOrder) {
            $this->executeTrade($order, $matchingOrder);
            
            if ($order->status !== 'open') {
                break;
            }
        }
    }

    /**
     * Execute trade between two orders.
     */
    protected function executeTrade(Order $order, Order $matchingOrder)
    {
        $tradeQuantity = min(
            $order->quantity - $order->filled_quantity,
            $matchingOrder->quantity - $matchingOrder->filled_quantity
        );

        $tradePrice = $matchingOrder->price ?? $order->price;

        // Execute the trade (this would create trade records and update wallets)
        // This is simplified for demonstration purposes

        $order->increment('filled_quantity', $tradeQuantity);
        $matchingOrder->increment('filled_quantity', $tradeQuantity);

        // Update order statuses
        if ($order->filled_quantity >= $order->quantity) {
            $order->update(['status' => 'filled']);
        } elseif ($order->filled_quantity > 0) {
            $order->update(['status' => 'partial']);
        }

        if ($matchingOrder->filled_quantity >= $matchingOrder->quantity) {
            $matchingOrder->update(['status' => 'filled']);
        } elseif ($matchingOrder->filled_quantity > 0) {
            $matchingOrder->update(['status' => 'partial']);
        }

        // Create trade record and update wallets would go here
    }

    /**
     * Get order book for a market.
     */
    public function orderBook($marketId)
    {
        $market = Market::findOrFail($marketId);

        $buyOrders = Order::where('market_id', $marketId)
            ->where('order_type', 'buy')
            ->where('status', 'open')
            ->orderBy('price', 'desc')
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy('price')
            ->map(function($orders) {
                return [
                    'price' => $orders->first()->price,
                    'quantity' => $orders->sum('quantity') - $orders->sum('filled_quantity'),
                    'orders' => $orders->count()
                ];
            })
            ->values();

        $sellOrders = Order::where('market_id', $marketId)
            ->where('order_type', 'sell')
            ->where('status', 'open')
            ->orderBy('price', 'asc')
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy('price')
            ->map(function($orders) {
                return [
                    'price' => $orders->first()->price,
                    'quantity' => $orders->sum('quantity') - $orders->sum('filled_quantity'),
                    'orders' => $orders->count()
                ];
            })
            ->values();

        return response()->json([
            'market' => $market->symbol,
            'buy_orders' => $buyOrders,
            'sell_orders' => $sellOrders
        ]);
    }

    /**
     * Get user's open orders.
     */
    public function openOrders()
    {
        $orders = Order::with('market')
            ->where('user_id', auth()->id())
            ->where('status', 'open')
            ->latest()
            ->get();

        return response()->json($orders);
    }

    /**
     * Bulk cancel user's orders.
     */
    public function bulkCancel(Request $request)
    {
        $validated = $request->validate([
            'market_id' => 'nullable|exists:markets,id',
            'order_type' => ['nullable', Rule::in(['buy', 'sell'])]
        ]);

        try {
            DB::beginTransaction();

            $query = Order::where('user_id', auth()->id())
                ->where('status', 'open');

            if ($validated['market_id'] ?? false) {
                $query->where('market_id', $validated['market_id']);
            }

            if ($validated['order_type'] ?? false) {
                $query->where('order_type', $validated['order_type']);
            }

            $orders = $query->get();

            foreach ($orders as $order) {
                $this->cancel($order);
            }

            DB::commit();

            return redirect()->back()
                ->with('success', "{$orders->count()} orders cancelled successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to cancel orders: ' . $e->getMessage());
        }
    }

    /**
     * Export orders (Admin only).
     */
    public function export(Request $request)
    {
        $this->authorize('export', Order::class);

        $validated = $request->validate([
            'format' => ['required', Rule::in(['csv', 'json'])],
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = Order::with(['user', 'market']);

        if ($request->has('start_date') && $request->start_date) {
            $query->where('created_at', '>=', $validated['start_date']);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('created_at', '<=', $validated['end_date'] . ' 23:59:59');
        }

        $orders = $query->get();

        if ($validated['format'] === 'csv') {
            return $this->exportToCsv($orders);
        }

        return $this->exportToJson($orders);
    }

    /**
     * Export orders to CSV.
     */
    protected function exportToCsv($orders)
    {
        $fileName = 'orders-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID', 'User', 'Market', 'Type', 'Kind', 'Price', 
                'Quantity', 'Filled', 'Status', 'Created At'
            ]);

            // Add data rows
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->user->name,
                    $order->market->symbol,
                    $order->order_type,
                    $order->order_kind,
                    $order->price,
                    $order->quantity,
                    $order->filled_quantity,
                    $order->status,
                    $order->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export orders to JSON.
     */
    protected function exportToJson($orders)
    {
        $fileName = 'orders-' . date('Y-m-d') . '.json';
        
        return response()->json($orders->toArray())
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}