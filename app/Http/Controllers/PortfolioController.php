<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Asset;
use App\Models\User;
use App\Models\Trade;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PortfolioController extends Controller
{
    public function __construct()
    {
        // Apply middleware
        $this->middleware('auth');
        $this->middleware('verified');
    }

    /**
     * Display the user's portfolio.
     */
    public function index(Request $request)
    {
        $query = Portfolio::with(['asset'])
            ->where('user_id', auth()->id());

        // Apply filters
        if ($request->has('asset_type') && $request->asset_type) {
            $query->whereHas('asset', function($q) use ($request) {
                $q->where('type', $request->asset_type);
            });
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('asset', function($q) use ($search) {
                    $q->where('symbol', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%");
                });
            });
        }

        // Sort by different criteria
        $sort = $request->get('sort', 'value_desc');
        switch ($sort) {
            case 'value_asc':
                $query->orderByRaw('quantity * avg_buy_price ASC');
                break;
            case 'value_desc':
                $query->orderByRaw('quantity * avg_buy_price DESC');
                break;
            case 'gain_asc':
                $query->orderByRaw('(quantity * (SELECT price FROM market_data WHERE asset_id = portfolio.asset_id ORDER BY created_at DESC LIMIT 1) - quantity * avg_buy_price) ASC');
                break;
            case 'gain_desc':
                $query->orderByRaw('(quantity * (SELECT price FROM market_data WHERE asset_id = portfolio.asset_id ORDER BY created_at DESC LIMIT 1) - quantity * avg_buy_price) DESC');
                break;
            case 'symbol_asc':
                $query->join('assets', 'portfolio.asset_id', '=', 'assets.id')
                      ->orderBy('assets.symbol', 'ASC');
                break;
            case 'symbol_desc':
                $query->join('assets', 'portfolio.asset_id', '=', 'assets.id')
                      ->orderBy('assets.symbol', 'DESC');
                break;
            default:
                $query->orderByRaw('quantity * avg_buy_price DESC');
        }

        $portfolio = $query->get();
        
        // Calculate totals
        $totalValue = 0;
        $totalCost = 0;
        $totalGain = 0;
        
        foreach ($portfolio as $item) {
            $currentPrice = $item->asset->latest_price ?? 0;
            $item->current_value = $item->quantity * $currentPrice;
            $item->total_cost = $item->quantity * $item->avg_buy_price;
            $item->unrealized_gain = $item->current_value - $item->total_cost;
            $item->gain_percentage = $item->total_cost > 0 ? 
                (($item->current_value - $item->total_cost) / $item->total_cost) * 100 : 0;
            
            $totalValue += $item->current_value;
            $totalCost += $item->total_cost;
            $totalGain += $item->unrealized_gain;
        }

        $assetTypes = Asset::distinct()->pluck('type');
        $sortOptions = [
            'value_desc' => 'Value (High to Low)',
            'value_asc' => 'Value (Low to High)',
            'gain_desc' => 'Gain (High to Low)',
            'gain_asc' => 'Gain (Low to High)',
            'symbol_asc' => 'Symbol (A-Z)',
            'symbol_desc' => 'Symbol (Z-A)',
        ];

        return view('portfolio.index', compact(
            'portfolio', 'totalValue', 'totalCost', 'totalGain', 
            'assetTypes', 'sortOptions'
        ));
    }

    /**
     * Display portfolio performance and statistics.
     */
    public function performance(Request $request)
    {
        $portfolio = Portfolio::with(['asset'])
            ->where('user_id', auth()->id())
            ->get();

        // Calculate performance metrics
        $metrics = $this->calculatePerformanceMetrics($portfolio);

        // Get recent trades
        $recentTrades = Trade::where(function($query) {
                $query->whereHas('buyOrder', function($q) {
                    $q->where('user_id', auth()->id());
                })->orWhereHas('sellOrder', function($q) {
                    $q->where('user_id', auth()->id());
                });
            })
            ->with(['market', 'buyOrder', 'sellOrder'])
            ->orderBy('trade_time', 'desc')
            ->limit(10)
            ->get();

        // Get allocation by asset type
        $allocationByType = $this->getAllocationByType($portfolio);

        // Get historical performance data (simplified)
        $historicalData = $this->getHistoricalPerformance();

        return view('portfolio.performance', compact(
            'metrics', 'recentTrades', 'allocationByType', 'historicalData'
        ));
    }

    /**
     * Display details for a specific portfolio item.
     */
    public function show(Asset $asset)
    {
        $portfolioItem = Portfolio::with(['asset'])
            ->where('user_id', auth()->id())
            ->where('asset_id', $asset->id)
            ->firstOrFail();

        // Get asset details
        $currentPrice = $portfolioItem->asset->latest_price ?? 0;
        $portfolioItem->current_value = $portfolioItem->quantity * $currentPrice;
        $portfolioItem->total_cost = $portfolioItem->quantity * $portfolioItem->avg_buy_price;
        $portfolioItem->unrealized_gain = $portfolioItem->current_value - $portfolioItem->total_cost;
        $portfolioItem->gain_percentage = $portfolioItem->total_cost > 0 ? 
            (($portfolioItem->current_value - $portfolioItem->total_cost) / $portfolioItem->total_cost) * 100 : 0;

        // Get trade history for this asset
        $trades = Trade::where(function($query) use ($asset) {
                $query->whereHas('buyOrder', function($q) use ($asset) {
                    $q->where('user_id', auth()->id())
                      ->whereHas('market', function($q2) use ($asset) {
                          $q2->where('base_asset', $asset->symbol);
                      });
                })->orWhereHas('sellOrder', function($q) use ($asset) {
                    $q->where('user_id', auth()->id())
                      ->whereHas('market', function($q2) use ($asset) {
                          $q2->where('base_asset', $asset->symbol);
                      });
                });
            })
            ->with(['market', 'buyOrder', 'sellOrder'])
            ->orderBy('trade_time', 'desc')
            ->paginate(20);

        // Get transaction history
        $transactions = Transaction::where('user_id', auth()->id())
            ->whereHas('wallet', function($q) use ($asset) {
                $q->where('currency', $asset->symbol);
            })
            ->orWhere('description', 'like', "%{$asset->symbol}%")
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('portfolio.show', compact('portfolioItem', 'trades', 'transactions'));
    }

    /**
     * Calculate performance metrics.
     */
    protected function calculatePerformanceMetrics($portfolio)
    {
        $totalValue = 0;
        $totalCost = 0;
        $totalGain = 0;
        $dailyGain = 0;

        foreach ($portfolio as $item) {
            $currentPrice = $item->asset->latest_price ?? 0;
            $previousPrice = $item->asset->previous_price ?? $currentPrice;
            
            $itemValue = $item->quantity * $currentPrice;
            $itemCost = $item->quantity * $item->avg_buy_price;
            $itemDailyGain = $item->quantity * ($currentPrice - $previousPrice);

            $totalValue += $itemValue;
            $totalCost += $itemCost;
            $totalGain += ($itemValue - $itemCost);
            $dailyGain += $itemDailyGain;
        }

        return [
            'total_value' => $totalValue,
            'total_cost' => $totalCost,
            'total_gain' => $totalGain,
            'total_gain_percentage' => $totalCost > 0 ? ($totalGain / $totalCost) * 100 : 0,
            'daily_gain' => $dailyGain,
            'daily_gain_percentage' => $totalValue > 0 ? ($dailyGain / $totalValue) * 100 : 0,
            'portfolio_size' => count($portfolio),
        ];
    }

    /**
     * Get allocation by asset type.
     */
    protected function getAllocationByType($portfolio)
    {
        $allocation = [];
        $totalValue = 0;

        foreach ($portfolio as $item) {
            $currentPrice = $item->asset->latest_price ?? 0;
            $itemValue = $item->quantity * $currentPrice;
            $assetType = $item->asset->type;

            if (!isset($allocation[$assetType])) {
                $allocation[$assetType] = 0;
            }

            $allocation[$assetType] += $itemValue;
            $totalValue += $itemValue;
        }

        // Convert to percentages
        foreach ($allocation as $type => $value) {
            $allocation[$type] = [
                'value' => $value,
                'percentage' => $totalValue > 0 ? ($value / $totalValue) * 100 : 0
            ];
        }

        return $allocation;
    }

    /**
     * Get historical performance data.
     */
    protected function getHistoricalPerformance()
    {
        // This is a simplified version - in production, you'd want to store
        // historical portfolio values and retrieve them here
        
        $data = [];
        $currentValue = Portfolio::where('user_id', auth()->id())
            ->get()
            ->sum(function($item) {
                $currentPrice = $item->asset->latest_price ?? 0;
                return $item->quantity * $currentPrice;
            });

        // Generate sample data for last 30 days
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            // Simulate some random fluctuation around current value
            $value = $currentValue * (0.9 + (mt_rand(0, 200) / 1000));
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'value' => $value
            ];
        }

        return $data;
    }

    /**
     * Export portfolio to CSV.
     */
    public function export()
    {
        $portfolio = Portfolio::with(['asset'])
            ->where('user_id', auth()->id())
            ->get();

        $fileName = 'portfolio-export-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($portfolio) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Symbol', 'Name', 'Type', 'Quantity', 
                'Avg Buy Price', 'Current Price', 'Total Cost',
                'Current Value', 'Unrealized Gain', 'Gain Percentage'
            ]);

            // Add data rows
            foreach ($portfolio as $item) {
                $currentPrice = $item->asset->latest_price ?? 0;
                $currentValue = $item->quantity * $currentPrice;
                $totalCost = $item->quantity * $item->avg_buy_price;
                $unrealizedGain = $currentValue - $totalCost;
                $gainPercentage = $totalCost > 0 ? ($unrealizedGain / $totalCost) * 100 : 0;

                fputcsv($file, [
                    $item->asset->symbol,
                    $item->asset->name,
                    $item->asset->type,
                    $item->quantity,
                    $item->avg_buy_price,
                    $currentPrice,
                    $totalCost,
                    $currentValue,
                    $unrealizedGain,
                    $gainPercentage
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get portfolio summary for API.
     */
    public function summary()
    {
        $portfolio = Portfolio::with(['asset'])
            ->where('user_id', auth()->id())
            ->get();

        $metrics = $this->calculatePerformanceMetrics($portfolio);
        $allocation = $this->getAllocationByType($portfolio);

        return response()->json([
            'total_value' => $metrics['total_value'],
            'total_gain' => $metrics['total_gain'],
            'total_gain_percentage' => $metrics['total_gain_percentage'],
            'daily_gain' => $metrics['daily_gain'],
            'daily_gain_percentage' => $metrics['daily_gain_percentage'],
            'allocation' => $allocation,
            'item_count' => $metrics['portfolio_size']
        ]);
    }

    /**
     * Get portfolio holdings for API.
     */
    public function holdings()
    {
        $holdings = Portfolio::with(['asset'])
            ->where('user_id', auth()->id())
            ->get()
            ->map(function($item) {
                $currentPrice = $item->asset->latest_price ?? 0;
                return [
                    'symbol' => $item->asset->symbol,
                    'name' => $item->asset->name,
                    'type' => $item->asset->type,
                    'quantity' => (float)$item->quantity,
                    'avg_buy_price' => (float)$item->avg_buy_price,
                    'current_price' => (float)$currentPrice,
                    'total_cost' => (float)($item->quantity * $item->avg_buy_price),
                    'current_value' => (float)($item->quantity * $currentPrice),
                    'unrealized_gain' => (float)($item->quantity * $currentPrice - $item->quantity * $item->avg_buy_price),
                ];
            });

        return response()->json($holdings);
    }

    /**
     * Add manual portfolio entry (for assets not from trades).
     */
    public function addManualEntry(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'quantity' => 'required|numeric|min:0.00000001',
            'avg_buy_price' => 'required|numeric|min:0.00000001',
            'acquisition_date' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $asset = Asset::findOrFail($validated['asset_id']);

            // Check if already exists in portfolio
            $existingEntry = Portfolio::where('user_id', auth()->id())
                ->where('asset_id', $asset->id)
                ->first();

            if ($existingEntry) {
                // Update existing entry using weighted average
                $totalQuantity = $existingEntry->quantity + $validated['quantity'];
                $totalCost = ($existingEntry->quantity * $existingEntry->avg_buy_price) + 
                            ($validated['quantity'] * $validated['avg_buy_price']);
                
                $newAvgPrice = $totalCost / $totalQuantity;

                $existingEntry->update([
                    'quantity' => $totalQuantity,
                    'avg_buy_price' => $newAvgPrice
                ]);

                $message = 'Portfolio entry updated successfully.';
            } else {
                // Create new entry
                Portfolio::create([
                    'user_id' => auth()->id(),
                    'asset_id' => $asset->id,
                    'quantity' => $validated['quantity'],
                    'avg_buy_price' => $validated['avg_buy_price'],
                ]);

                $message = 'Portfolio entry added successfully.';
            }

            // Create transaction record
            Transaction::create([
                'user_id' => auth()->id(),
                'type' => 'manual_entry',
                'amount' => $validated['quantity'],
                'fee' => 0,
                'status' => 'completed',
                'description' => "Manual portfolio entry: {$asset->symbol} - {$validated['quantity']} shares @ {$validated['avg_buy_price']}",
            ]);

            DB::commit();

            return redirect()->route('portfolio.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to add portfolio entry: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove manual portfolio entry.
     */
    public function removeManualEntry(Portfolio $portfolio)
    {
        $this->authorize('delete', $portfolio);

        try {
            DB::beginTransaction();

            // Create transaction record for removal
            Transaction::create([
                'user_id' => auth()->id(),
                'type' => 'manual_removal',
                'amount' => $portfolio->quantity,
                'fee' => 0,
                'status' => 'completed',
                'description' => "Manual portfolio removal: {$portfolio->asset->symbol} - {$portfolio->quantity} shares",
            ]);

            $portfolio->delete();

            DB::commit();

            return redirect()->route('portfolio.index')
                ->with('success', 'Portfolio entry removed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to remove portfolio entry: ' . $e->getMessage());
        }
    }

    /**
     * Show manual entry form.
     */
    public function showManualEntryForm()
    {
        $assets = Asset::where('status', true)->get();
        return view('portfolio.manual-entry', compact('assets'));
    }
}