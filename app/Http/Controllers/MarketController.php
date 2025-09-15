<?php

namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MarketController extends Controller
{
    public function __construct()
    {
        // Apply middleware - only admins can manage markets
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Market::with(['baseAsset', 'quoteAsset']);

        // Apply filters
        if ($request->has('market_type') && $request->market_type) {
            $query->where('market_type', $request->market_type);
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('base_asset') && $request->base_asset) {
            $query->where('base_asset', $request->base_asset);
        }

        if ($request->has('quote_asset') && $request->quote_asset) {
            $query->where('quote_asset', $request->quote_asset);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('base_asset', 'like', "%{$search}%")
                  ->orWhere('quote_asset', 'like', "%{$search}%");
            });
        }

        $markets = $query->latest()->paginate(20);
        
        $marketTypes = [
            'spot' => 'Spot',
            'margin' => 'Margin',
            'futures' => 'Futures'
        ];

        $baseAssets = Asset::where('status', true)->get();
        $quoteAssets = Asset::where('status', true)->where('type', '!=', 'crypto')->get();

        return view('markets.index', compact('markets', 'marketTypes', 'baseAssets', 'quoteAssets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $marketTypes = [
            'spot' => 'Spot',
            'margin' => 'Margin',
            'futures' => 'Futures'
        ];

        $baseAssets = Asset::where('status', true)->get();
        $quoteAssets = Asset::where('status', true)->where('type', '!=', 'crypto')->get();

        return view('markets.create', compact('marketTypes', 'baseAssets', 'quoteAssets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'base_asset' => 'required|string|exists:assets,symbol',
            'quote_asset' => 'required|string|exists:assets,symbol',
            'market_type' => ['required', Rule::in(['spot','margin','futures'])],
            'min_order_size' => 'required|numeric|min:0',
            'max_order_size' => 'required|numeric|min:0|gt:min_order_size',
            'fee_rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|boolean',
        ]);

        // Check if market already exists
        $existingMarket = Market::where('base_asset', $validated['base_asset'])
            ->where('quote_asset', $validated['quote_asset'])
            ->where('market_type', $validated['market_type'])
            ->exists();

        if ($existingMarket) {
            return redirect()->back()
                ->with('error', 'Market already exists with these parameters.')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            Market::create($validated);

            DB::commit();

            return redirect()->route('markets.index')
                ->with('success', 'Market created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create market: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Market $market)
    {
        $market->load(['baseAsset', 'quoteAsset', 'orders', 'trades']);
        
        return view('markets.show', compact('market'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Market $market)
    {
        $marketTypes = [
            'spot' => 'Spot',
            'margin' => 'Margin',
            'futures' => 'Futures'
        ];

        $baseAssets = Asset::where('status', true)->get();
        $quoteAssets = Asset::where('status', true)->where('type', '!=', 'crypto')->get();

        return view('markets.edit', compact('market', 'marketTypes', 'baseAssets', 'quoteAssets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Market $market)
    {
        $validated = $request->validate([
            'base_asset' => 'required|string|exists:assets,symbol',
            'quote_asset' => 'required|string|exists:assets,symbol',
            'market_type' => ['required', Rule::in(['spot','margin','futures'])],
            'min_order_size' => 'required|numeric|min:0',
            'max_order_size' => 'required|numeric|min:0|gt:min_order_size',
            'fee_rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|boolean',
        ]);

        // Check if market already exists (excluding current market)
        $existingMarket = Market::where('base_asset', $validated['base_asset'])
            ->where('quote_asset', $validated['quote_asset'])
            ->where('market_type', $validated['market_type'])
            ->where('id', '!=', $market->id)
            ->exists();

        if ($existingMarket) {
            return redirect()->back()
                ->with('error', 'Another market already exists with these parameters.')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $market->update($validated);

            DB::commit();

            return redirect()->route('markets.index')
                ->with('success', 'Market updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update market: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Market $market)
    {
        try {
            DB::beginTransaction();

            // Check if market is being used in orders or trades
            if ($this->isMarketInUse($market)) {
                return redirect()->route('markets.index')
                    ->with('error', 'Cannot delete market. It is being used in orders or trades.');
            }

            $market->delete();

            DB::commit();

            return redirect()->route('markets.index')
                ->with('success', 'Market deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('markets.index')
                ->with('error', 'Failed to delete market: ' . $e->getMessage());
        }
    }

    /**
     * Check if market is being used in orders or trades.
     */
    protected function isMarketInUse(Market $market): bool
    {
        // Check if market has orders
        if ($market->orders()->exists()) {
            return true;
        }

        // Check if market has trades
        if ($market->trades()->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Toggle market status.
     */
    public function toggleStatus(Market $market)
    {
        try {
            DB::beginTransaction();

            $market->update([
                'status' => !$market->status
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Market status updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update market status: ' . $e->getMessage());
        }
    }

    /**
     * Bulk import markets from CSV.
     */
    public function import(Request $request)
    {
        $validated = $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('csv_file');
            $csvData = array_map('str_getcsv', file($file->getPathname()));
            
            $headers = array_shift($csvData);
            $imported = 0;
            $skipped = 0;

            foreach ($csvData as $row) {
                $data = array_combine($headers, $row);

                // Validate required fields
                if (empty($data['base_asset']) || empty($data['quote_asset']) || empty($data['market_type'])) {
                    $skipped++;
                    continue;
                }

                // Check if assets exist
                $baseAsset = Asset::where('symbol', $data['base_asset'])->first();
                $quoteAsset = Asset::where('symbol', $data['quote_asset'])->first();

                if (!$baseAsset || !$quoteAsset) {
                    $skipped++;
                    continue;
                }

                // Check if market already exists
                $existingMarket = Market::where('base_asset', $data['base_asset'])
                    ->where('quote_asset', $data['quote_asset'])
                    ->where('market_type', $data['market_type'])
                    ->exists();

                if ($existingMarket) {
                    $skipped++;
                    continue;
                }

                // Create market
                Market::create([
                    'base_asset' => $data['base_asset'],
                    'quote_asset' => $data['quote_asset'],
                    'market_type' => $data['market_type'],
                    'min_order_size' => $data['min_order_size'] ?? 0,
                    'max_order_size' => $data['max_order_size'] ?? 0,
                    'fee_rate' => $data['fee_rate'] ?? 0,
                    'status' => isset($data['status']) ? (bool)$data['status'] : true,
                ]);

                $imported++;
            }

            DB::commit();

            return redirect()->route('markets.index')
                ->with('success', "Markets imported successfully. Imported: {$imported}, Skipped: {$skipped}");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to import markets: ' . $e->getMessage());
        }
    }

    /**
     * Export markets to CSV.
     */
    public function export()
    {
        $markets = Market::with(['baseAsset', 'quoteAsset'])->get();

        $fileName = 'markets-export-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($markets) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Base Asset', 'Quote Asset', 'Market Type', 
                'Min Order Size', 'Max Order Size', 'Fee Rate', 
                'Status', 'Created At'
            ]);

            // Add data rows
            foreach ($markets as $market) {
                fputcsv($file, [
                    $market->base_asset,
                    $market->quote_asset,
                    $market->market_type,
                    $market->min_order_size,
                    $market->max_order_size,
                    $market->fee_rate,
                    $market->status ? 'Active' : 'Inactive',
                    $market->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get markets for API.
     */
    public function apiIndex(Request $request)
    {
        $query = Market::with(['baseAsset', 'quoteAsset'])
            ->where('status', true);

        if ($request->has('market_type')) {
            $query->where('market_type', $request->market_type);
        }

        if ($request->has('base_asset')) {
            $query->where('base_asset', $request->base_asset);
        }

        if ($request->has('quote_asset')) {
            $query->where('quote_asset', $request->quote_asset);
        }

        $markets = $query->get()->map(function($market) {
            return [
                'id' => $market->id,
                'symbol' => $market->base_asset . '/' . $market->quote_asset,
                'base_asset' => $market->base_asset,
                'quote_asset' => $market->quote_asset,
                'market_type' => $market->market_type,
                'min_order_size' => (float)$market->min_order_size,
                'max_order_size' => (float)$market->max_order_size,
                'fee_rate' => (float)$market->fee_rate,
                'created_at' => $market->created_at,
            ];
        });

        return response()->json($markets);
    }

    /**
     * Show import form.
     */
    public function showImportForm()
    {
        return view('markets.import');
    }

    /**
     * Get market statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_markets' => Market::count(),
            'active_markets' => Market::where('status', true)->count(),
            'inactive_markets' => Market::where('status', false)->count(),
            'by_type' => Market::selectRaw('market_type, COUNT(*) as count')
                ->groupBy('market_type')
                ->get()
                ->pluck('count', 'market_type'),
            'top_base_assets' => Market::selectRaw('base_asset, COUNT(*) as count')
                ->groupBy('base_asset')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'top_quote_assets' => Market::selectRaw('quote_asset, COUNT(*) as count')
                ->groupBy('quote_asset')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
        ];

        return view('markets.statistics', compact('stats'));
    }

    /**
     * Get market by symbol.
     */
    public function getBySymbol($symbol)
    {
        $parts = explode('/', $symbol);
        if (count($parts) !== 2) {
            return response()->json(['error' => 'Invalid market symbol format'], 400);
        }

        $market = Market::with(['baseAsset', 'quoteAsset'])
            ->where('base_asset', $parts[0])
            ->where('quote_asset', $parts[1])
            ->where('status', true)
            ->first();

        if (!$market) {
            return response()->json(['error' => 'Market not found'], 404);
        }

        return response()->json([
            'id' => $market->id,
            'symbol' => $market->base_asset . '/' . $market->quote_asset,
            'base_asset' => $market->base_asset,
            'quote_asset' => $market->quote_asset,
            'market_type' => $market->market_type,
            'min_order_size' => (float)$market->min_order_size,
            'max_order_size' => (float)$market->max_order_size,
            'fee_rate' => (float)$market->fee_rate,
        ]);
    }
}