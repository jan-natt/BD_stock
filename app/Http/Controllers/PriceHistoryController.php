<?php

namespace App\Http\Controllers;

use App\Models\PriceHistory;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class PriceHistoryController extends Controller
{
    public function __construct()
    {
        // Apply middleware - price history data is read-only for most users
        $this->middleware('auth');
        $this->middleware('admin')->except(['index', 'show', 'chartData', 'historicalData']);
    }

    /**
     * Display a listing of price history data (Admin only).
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', PriceHistory::class);

        $query = PriceHistory::with(['asset']);

        // Apply filters
        if ($request->has('asset_id') && $request->asset_id) {
            $query->where('asset_id', $request->asset_id);
        }

        if ($request->has('timeframe') && $request->timeframe) {
            $timeframe = $this->getTimeframeFilter($request->timeframe);
            $query->where('timestamp', '>=', $timeframe);
        }

        if ($request->has('start_date') && $request->start_date) {
            $query->where('timestamp', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('timestamp', '<=', $request->end_date . ' 23:59:59');
        }

        if ($request->has('min_price') && $request->min_price) {
            $query->where('close', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->where('close', '<=', $request->max_price);
        }

        $priceHistory = $query->orderBy('timestamp', 'desc')->paginate(50);
        
        $assets = Asset::where('status', true)->get();
        $timeframes = [
            '24h' => 'Last 24 Hours',
            '7d' => 'Last 7 Days',
            '30d' => 'Last 30 Days',
            '90d' => 'Last 90 Days',
            '1y' => 'Last Year',
        ];

        return view('price-history.index', compact('priceHistory', 'assets', 'timeframes'));
    }

    /**
     * Display chart data for a specific asset.
     */
    public function chartData(Request $request, $assetId)
    {
        $asset = Asset::findOrFail($assetId);
        $this->authorize('view', $asset);

        $validated = $request->validate([
            'timeframe' => 'required|in:1h,24h,7d,30d,90d,1y,all',
            'interval' => 'required|in:1m,5m,15m,30m,1h,4h,1d,1w,1M',
        ]);

        $cacheKey = "chart_data_{$assetId}_{$validated['timeframe']}_{$validated['interval']}";
        $chartData = Cache::remember($cacheKey, 300, function() use ($asset, $validated) {
            return $this->getChartData($asset, $validated['timeframe'], $validated['interval']);
        });

        return response()->json($chartData);
    }

    /**
     * Get chart data based on timeframe and interval.
     */
    protected function getChartData(Asset $asset, $timeframe, $interval)
    {
        $query = PriceHistory::where('asset_id', $asset->id);

        // Apply timeframe filter
        $startDate = $this->getStartDateFromTimeframe($timeframe);
        if ($startDate) {
            $query->where('timestamp', '>=', $startDate);
        }

        // Apply interval grouping
        $data = $this->groupDataByInterval($query, $interval);

        return [
            'asset' => [
                'symbol' => $asset->symbol,
                'name' => $asset->name,
            ],
            'timeframe' => $timeframe,
            'interval' => $interval,
            'data' => $data,
            'metadata' => $this->getChartMetadata($data)
        ];
    }

    /**
     * Get start date from timeframe.
     */
    protected function getStartDateFromTimeframe($timeframe)
    {
        return match($timeframe) {
            '1h' => now()->subHour(),
            '24h' => now()->subDay(),
            '7d' => now()->subDays(7),
            '30d' => now()->subDays(30),
            '90d' => now()->subDays(90),
            '1y' => now()->subYear(),
            default => null // 'all'
        };
    }

    /**
     * Group data by interval.
     */
    protected function groupDataByInterval($query, $interval)
    {
        $rawSelect = match($interval) {
            '1m' => "DATE_FORMAT(timestamp, '%Y-%m-%d %H:%i:00')",
            '5m' => "CONCAT(DATE_FORMAT(timestamp, '%Y-%m-%d %H:'), LPAD(FLOOR(MINUTE(timestamp)/5)*5, 2, '0'), ':00')",
            '15m' => "CONCAT(DATE_FORMAT(timestamp, '%Y-%m-%d %H:'), LPAD(FLOOR(MINUTE(timestamp)/15)*15, 2, '0'), ':00')",
            '30m' => "CONCAT(DATE_FORMAT(timestamp, '%Y-%m-%d %H:'), LPAD(FLOOR(MINUTE(timestamp)/30)*30, 2, '0'), ':00')",
            '1h' => "DATE_FORMAT(timestamp, '%Y-%m-%d %H:00:00')",
            '4h' => "CONCAT(DATE_FORMAT(timestamp, '%Y-%m-%d '), LPAD(FLOOR(HOUR(timestamp)/4)*4, 2, '0'), ':00:00')",
            '1d' => "DATE(timestamp)",
            '1w' => "DATE_FORMAT(timestamp, '%x-%v')", // Year-week
            '1M' => "DATE_FORMAT(timestamp, '%Y-%m-01')",
        };

        return $query->select(
            DB::raw("{$rawSelect} as time_group"),
            DB::raw('FIRST(open) as open'),
            DB::raw('MAX(high) as high'),
            DB::raw('MIN(low) as low'),
            DB::raw('LAST(close) as close'),
            DB::raw('SUM(volume) as volume')
        )
        ->groupBy('time_group')
        ->orderBy('time_group')
        ->get();
    }

    /**
     * Get chart metadata.
     */
    protected function getChartMetadata($data)
    {
        if ($data->isEmpty()) {
            return [
                'current_price' => 0,
                'price_change' => 0,
                'price_change_percent' => 0,
                'high_24h' => 0,
                'low_24h' => 0,
                'volume_24h' => 0
            ];
        }

        $first = $data->first();
        $last = $data->last();
        $priceChange = $last->close - $first->open;
        $priceChangePercent = $first->open > 0 ? ($priceChange / $first->open) * 100 : 0;

        // Get 24h high/low from last 24 hours data
        $high24h = $data->max('high');
        $low24h = $data->min('low');
        $volume24h = $data->sum('volume');

        return [
            'current_price' => (float)$last->close,
            'price_change' => (float)$priceChange,
            'price_change_percent' => (float)$priceChangePercent,
            'high_24h' => (float)$high24h,
            'low_24h' => (float)$low24h,
            'volume_24h' => (float)$volume24h
        ];
    }

    /**
     * Display historical data for download.
     */
    public function historicalData(Request $request, $assetId)
    {
        $asset = Asset::findOrFail($assetId);
        $this->authorize('view', $asset);

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:json,csv'
        ]);

        $data = PriceHistory::where('asset_id', $assetId)
            ->whereBetween('timestamp', [$validated['start_date'], $validated['end_date'] . ' 23:59:59'])
            ->orderBy('timestamp')
            ->get();

        if ($validated['format'] === 'csv') {
            return $this->exportToCSV($data, $asset);
        }

        return response()->json([
            'asset' => $asset,
            'timeframe' => [
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date']
            ],
            'data' => $data
        ]);
    }

    /**
     * Export data to CSV.
     */
    protected function exportToCSV($data, $asset)
    {
        $fileName = "{$asset->symbol}_historical_data_" . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($data, $asset) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['Timestamp', 'Open', 'High', 'Low', 'Close', 'Volume', 'Asset']);

            // Add data rows
            foreach ($data as $record) {
                fputcsv($file, [
                    $record->timestamp,
                    $record->open,
                    $record->high,
                    $record->low,
                    $record->close,
                    $record->volume,
                    $asset->symbol
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the form for creating new price history data (Admin only).
     */
    public function create()
    {
        $this->authorize('create', PriceHistory::class);

        $assets = Asset::where('status', true)->get();
        return view('price-history.create', compact('assets'));
    }

    /**
     * Store newly created price history data (Admin only).
     */
    public function store(Request $request)
    {
        $this->authorize('create', PriceHistory::class);

        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'timestamp' => 'required|date',
            'open' => 'required|numeric|min:0',
            'high' => 'required|numeric|min:0|gte:open',
            'low' => 'required|numeric|min:0|lte:open',
            'close' => 'required|numeric|min:0',
            'volume' => 'required|numeric|min:0',
        ]);

        // Validate that high is the highest and low is the lowest
        if ($validated['high'] < $validated['low']) {
            return redirect()->back()
                ->with('error', 'High price must be greater than or equal to low price.')
                ->withInput();
        }

        if ($validated['close'] > $validated['high'] || $validated['close'] < $validated['low']) {
            return redirect()->back()
                ->with('error', 'Close price must be between low and high prices.')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Check if data already exists for this timestamp
            $existing = PriceHistory::where('asset_id', $validated['asset_id'])
                ->where('timestamp', $validated['timestamp'])
                ->exists();

            if ($existing) {
                return redirect()->back()
                    ->with('error', 'Price data already exists for this timestamp.')
                    ->withInput();
            }

            PriceHistory::create($validated);

            // Clear cache for this asset
            $this->clearAssetCache($validated['asset_id']);

            DB::commit();

            return redirect()->route('price-history.index')
                ->with('success', 'Price history data added successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to add price history data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Bulk import price history data (Admin only).
     */
    public function bulkImport(Request $request)
    {
        $this->authorize('create', PriceHistory::class);

        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
            'timeframe' => 'required|in:1m,5m,15m,30m,1h,4h,1d',
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
                if (empty($data['timestamp']) || empty($data['open']) || empty($data['high']) || 
                    empty($data['low']) || empty($data['close']) || empty($data['volume'])) {
                    $skipped++;
                    continue;
                }

                // Parse timestamp based on timeframe
                $timestamp = $this->parseTimestamp($data['timestamp'], $validated['timeframe']);

                // Check if data already exists
                $existing = PriceHistory::where('asset_id', $validated['asset_id'])
                    ->where('timestamp', $timestamp)
                    ->exists();

                if ($existing) {
                    $skipped++;
                    continue;
                }

                // Create record
                PriceHistory::create([
                    'asset_id' => $validated['asset_id'],
                    'timestamp' => $timestamp,
                    'open' => $data['open'],
                    'high' => $data['high'],
                    'low' => $data['low'],
                    'close' => $data['close'],
                    'volume' => $data['volume'],
                ]);

                $imported++;
            }

            // Clear cache for this asset
            $this->clearAssetCache($validated['asset_id']);

            DB::commit();

            return redirect()->route('price-history.index')
                ->with('success', "Price history imported successfully. Imported: {$imported}, Skipped: {$skipped}");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to import price history: ' . $e->getMessage());
        }
    }

    /**
     * Parse timestamp based on timeframe.
     */
    protected function parseTimestamp($timestamp, $timeframe)
    {
        try {
            $date = Carbon::parse($timestamp);
            
            // Round to the nearest interval
            return match($timeframe) {
                '1m' => $date->second(0),
                '5m' => $date->minute($date->minute - ($date->minute % 5))->second(0),
                '15m' => $date->minute($date->minute - ($date->minute % 15))->second(0),
                '30m' => $date->minute($date->minute - ($date->minute % 30))->second(0),
                '1h' => $date->minute(0)->second(0),
                '4h' => $date->hour($date->hour - ($date->hour % 4))->minute(0)->second(0),
                '1d' => $date->startOfDay(),
            };
        } catch (\Exception $e) {
            throw new \Exception("Invalid timestamp format: {$timestamp}");
        }
    }

    /**
     * Clear cache for an asset.
     */
    protected function clearAssetCache($assetId)
    {
        $cachePattern = "chart_data_{$assetId}_*";
        Cache::flush($cachePattern);
    }

    /**
     * Show technical indicators for an asset.
     */
    public function technicalIndicators($assetId)
    {
        $asset = Asset::findOrFail($assetId);
        $this->authorize('view', $asset);

        $data = PriceHistory::where('asset_id', $assetId)
            ->orderBy('timestamp', 'desc')
            ->limit(100)
            ->get()
            ->reverse()
            ->values();

        if ($data->isEmpty()) {
            return response()->json(['error' => 'No price data available'], 404);
        }

        $indicators = $this->calculateTechnicalIndicators($data);

        return response()->json([
            'asset' => $asset,
            'indicators' => $indicators,
            'last_updated' => $data->last()->timestamp
        ]);
    }

    /**
     * Calculate technical indicators.
     */
    protected function calculateTechnicalIndicators($data)
    {
        $prices = $data->pluck('close')->toArray();
        $volumes = $data->pluck('volume')->toArray();

        return [
            'sma' => $this->calculateSMA($prices, 20),
            'ema' => $this->calculateEMA($prices, 20),
            'rsi' => $this->calculateRSI($prices, 14),
            'macd' => $this->calculateMACD($prices),
            'bollinger_bands' => $this->calculateBollingerBands($prices, 20),
            'volume_avg' => array_sum($volumes) / count($volumes),
        ];
    }

    /**
     * Calculate Simple Moving Average.
     */
    protected function calculateSMA($prices, $period)
    {
        if (count($prices) < $period) {
            return null;
        }

        $slices = array_slice($prices, -$period);
        return array_sum($slices) / $period;
    }

    /**
     * Calculate Exponential Moving Average.
     */
    protected function calculateEMA($prices, $period)
    {
        if (count($prices) < $period) {
            return null;
        }

        $slices = array_slice($prices, -$period);
        $multiplier = 2 / ($period + 1);
        $ema = $slices[0];

        for ($i = 1; $i < count($slices); $i++) {
            $ema = ($slices[$i] - $ema) * $multiplier + $ema;
        }

        return $ema;
    }

    /**
     * Calculate Relative Strength Index.
     */
    protected function calculateRSI($prices, $period)
    {
        if (count($prices) <= $period) {
            return null;
        }

        $deltas = [];
        for ($i = 1; $i < count($prices); $i++) {
            $deltas[] = $prices[$i] - $prices[$i - 1];
        }

        $gains = $losses = [];
        foreach ($deltas as $delta) {
            $gains[] = $delta > 0 ? $delta : 0;
            $losses[] = $delta < 0 ? abs($delta) : 0;
        }

        $avgGain = array_sum(array_slice($gains, 0, $period)) / $period;
        $avgLoss = array_sum(array_slice($losses, 0, $period)) / $period;

        if ($avgLoss == 0) {
            return 100;
        }

        $rs = $avgGain / $avgLoss;
        return 100 - (100 / (1 + $rs));
    }

    /**
     * Calculate MACD.
     */
    protected function calculateMACD($prices)
    {
        if (count($prices) < 26) {
            return null;
        }

        $ema12 = $this->calculateEMA($prices, 12);
        $ema26 = $this->calculateEMA($prices, 26);

        if ($ema12 === null || $ema26 === null) {
            return null;
        }

        return [
            'macd' => $ema12 - $ema26,
            'signal' => $this->calculateEMA(array_slice($prices, -9), 9),
            'histogram' => ($ema12 - $ema26) - $this->calculateEMA(array_slice($prices, -9), 9)
        ];
    }

    /**
     * Calculate Bollinger Bands.
     */
    protected function calculateBollingerBands($prices, $period)
    {
        if (count($prices) < $period) {
            return null;
        }

        $slices = array_slice($prices, -$period);
        $sma = array_sum($slices) / $period;
        
        $variance = 0;
        foreach ($slices as $price) {
            $variance += pow($price - $sma, 2);
        }
        $stdDev = sqrt($variance / $period);

        return [
            'upper' => $sma + (2 * $stdDev),
            'middle' => $sma,
            'lower' => $sma - (2 * $stdDev)
        ];
    }

    /**
     * Get price alerts for an asset.
     */
    public function priceAlerts($assetId)
    {
        $asset = Asset::findOrFail($assetId);
        $this->authorize('view', $asset);

        $currentPrice = PriceHistory::where('asset_id', $assetId)
            ->orderBy('timestamp', 'desc')
            ->first()
            ->close ?? 0;

        // This would typically come from a user alerts system
        $alerts = [
            'support_levels' => $this->calculateSupportLevels($assetId),
            'resistance_levels' => $this->calculateResistanceLevels($assetId),
            'price_targets' => $this->calculatePriceTargets($assetId),
        ];

        return response()->json([
            'asset' => $asset,
            'current_price' => $currentPrice,
            'alerts' => $alerts
        ]);
    }

    /**
     * Calculate support levels.
     */
    protected function calculateSupportLevels($assetId)
    {
        $data = PriceHistory::where('asset_id', $assetId)
            ->orderBy('timestamp', 'desc')
            ->limit(200)
            ->get();

        // Simplified support level calculation
        $lows = $data->pluck('low')->toArray();
        return [
            'immediate' => min($lows),
            'major' => min($lows) * 0.95,
            'critical' => min($lows) * 0.90
        ];
    }

    /**
     * Calculate resistance levels.
     */
    protected function calculateResistanceLevels($assetId)
    {
        $data = PriceHistory::where('asset_id', $assetId)
            ->orderBy('timestamp', 'desc')
            ->limit(200)
            ->get();

        // Simplified resistance level calculation
        $highs = $data->pluck('high')->toArray();
        return [
            'immediate' => max($highs),
            'major' => max($highs) * 1.05,
            'critical' => max($highs) * 1.10
        ];
    }

    /**
     * Calculate price targets.
     */
    protected function calculatePriceTargets($assetId)
    {
        // Simplified price target calculation based on recent momentum
        $data = PriceHistory::where('asset_id', $assetId)
            ->orderBy('timestamp', 'desc')
            ->limit(50)
            ->get();

        if ($data->count() < 2) {
            return [];
        }

        $first = $data->last();
        $last = $data->first();
        $change = $last->close - $first->close;
        $changePercent = $first->close > 0 ? ($change / $first->close) * 100 : 0;

        return [
            'short_term' => $last->close * (1 + $changePercent / 100),
            'medium_term' => $last->close * (1 + $changePercent / 50),
            'long_term' => $last->close * (1 + $changePercent / 25)
        ];
    }
}