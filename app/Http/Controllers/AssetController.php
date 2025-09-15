<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AssetController extends Controller
{
    public function __construct()
    {
        // Apply middleware - only admins can manage assets
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Asset::query();

        // Apply filters
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('symbol', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $assets = $query->latest()->paginate(20);
        
        $assetTypes = [
            'stock' => 'Stock',
            'crypto' => 'Cryptocurrency',
            'forex' => 'Forex',
            'commodity' => 'Commodity',
            'ipo' => 'IPO'
        ];

        return view('assets.index', compact('assets', 'assetTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assetTypes = [
            'stock' => 'Stock',
            'crypto' => 'Cryptocurrency',
            'forex' => 'Forex',
            'commodity' => 'Commodity',
            'ipo' => 'IPO'
        ];

        return view('assets.create', compact('assetTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'symbol' => 'required|string|max:20|unique:assets,symbol',
            'name' => 'required|string|max:255',
            'type' => ['required', Rule::in(['stock','crypto','forex','commodity','ipo'])],
            'precision' => 'required|integer|min:0|max:18',
            'status' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();

            Asset::create($validated);

            DB::commit();

            return redirect()->route('assets.index')
                ->with('success', 'Asset created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create asset: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        // Load additional data based on asset type
        $asset->loadCount(['marketData', 'trades']);
        
        return view('assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        $assetTypes = [
            'stock' => 'Stock',
            'crypto' => 'Cryptocurrency',
            'forex' => 'Forex',
            'commodity' => 'Commodity',
            'ipo' => 'IPO'
        ];

        return view('assets.edit', compact('asset', 'assetTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'symbol' => [
                'required',
                'string',
                'max:20',
                Rule::unique('assets', 'symbol')->ignore($asset->id)
            ],
            'name' => 'required|string|max:255',
            'type' => ['required', Rule::in(['stock','crypto','forex','commodity','ipo'])],
            'precision' => 'required|integer|min:0|max:18',
            'status' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();

            $asset->update($validated);

            DB::commit();

            return redirect()->route('assets.index')
                ->with('success', 'Asset updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update asset: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        try {
            DB::beginTransaction();

            // Check if asset is being used in trades or market data
            if ($this->isAssetInUse($asset)) {
                return redirect()->route('assets.index')
                    ->with('error', 'Cannot delete asset. It is being used in trades or market data.');
            }

            $asset->delete();

            DB::commit();

            return redirect()->route('assets.index')
                ->with('success', 'Asset deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('assets.index')
                ->with('error', 'Failed to delete asset: ' . $e->getMessage());
        }
    }

    /**
     * Check if asset is being used in trades or market data.
     */
    protected function isAssetInUse(Asset $asset): bool
    {
        // Check if asset has trades
        if ($asset->trades()->exists()) {
            return true;
        }

        // Check if asset has market data
        if ($asset->marketData()->exists()) {
            return true;
        }

        // Check if asset is used in any user wallets
        // if ($asset->wallets()->exists()) {
        //     return true;
        // }

        return false;
    }

    /**
     * Toggle asset status.
     */
    public function toggleStatus(Asset $asset)
    {
        try {
            DB::beginTransaction();

            $asset->update([
                'status' => !$asset->status
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Asset status updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update asset status: ' . $e->getMessage());
        }
    }

    /**
     * Bulk import assets from CSV.
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
                if (empty($data['symbol']) || empty($data['name']) || empty($data['type'])) {
                    $skipped++;
                    continue;
                }

                // Check if asset already exists
                if (Asset::where('symbol', $data['symbol'])->exists()) {
                    $skipped++;
                    continue;
                }

                // Create asset
                Asset::create([
                    'symbol' => $data['symbol'],
                    'name' => $data['name'],
                    'type' => $data['type'],
                    'precision' => $data['precision'] ?? 8,
                    'status' => isset($data['status']) ? (bool)$data['status'] : true,
                ]);

                $imported++;
            }

            DB::commit();

            return redirect()->route('assets.index')
                ->with('success', "Assets imported successfully. Imported: {$imported}, Skipped: {$skipped}");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to import assets: ' . $e->getMessage());
        }
    }

    /**
     * Export assets to CSV.
     */
    public function export()
    {
        $assets = Asset::all();

        $fileName = 'assets-export-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($assets) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['Symbol', 'Name', 'Type', 'Precision', 'Status', 'Created At']);

            // Add data rows
            foreach ($assets as $asset) {
                fputcsv($file, [
                    $asset->symbol,
                    $asset->name,
                    $asset->type,
                    $asset->precision,
                    $asset->status ? 'Active' : 'Inactive',
                    $asset->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get assets for API.
     */
    public function apiIndex(Request $request)
    {
        $query = Asset::where('status', true);

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('symbol', 'like', "%{$request->search}%")
                  ->orWhere('name', 'like', "%{$request->search}%");
            });
        }

        $assets = $query->get()->map(function($asset) {
            return [
                'id' => $asset->id,
                'symbol' => $asset->symbol,
                'name' => $asset->name,
                'type' => $asset->type,
                'precision' => $asset->precision,
                'created_at' => $asset->created_at,
            ];
        });

        return response()->json($assets);
    }

    /**
     * Show import form.
     */
    public function showImportForm()
    {
        return view('assets.import');
    }

    /**
     * Get asset statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_assets' => Asset::count(),
            'active_assets' => Asset::where('status', true)->count(),
            'inactive_assets' => Asset::where('status', false)->count(),
            'by_type' => Asset::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get()
                ->pluck('count', 'type'),
        ];

        return view('assets.statistics', compact('stats'));
    }
}