<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Portfolio extends Model
{
    use HasFactory;

    protected $table = 'portfolio';

    protected $fillable = [
        'user_id',
        'asset_id',
        'quantity',
        'avg_buy_price'
    ];

    protected $casts = [
        'quantity' => 'decimal:8',
        'avg_buy_price' => 'decimal:8'
    ];

    /**
     * Get the user that owns the portfolio item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the asset that owns the portfolio item.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Scope a query to only include items with positive quantity.
     */
    public function scopeActive($query)
    {
        return $query->where('quantity', '>', 0);
    }

    /**
     * Scope a query to only include items for specific asset types.
     */
    public function scopeOfType($query, $type)
    {
        return $query->whereHas('asset', function($q) use ($type) {
            $q->where('type', $type);
        });
    }

    /**
     * Get the total cost basis.
     */
    public function getTotalCostAttribute(): float
    {
        return $this->quantity * $this->avg_buy_price;
    }

    /**
     * Get the current value.
     */
    public function getCurrentValueAttribute(): float
    {
        $currentPrice = $this->asset->latest_price ?? 0;
        return $this->quantity * $currentPrice;
    }

    /**
     * Get the unrealized gain/loss.
     */
    public function getUnrealizedGainAttribute(): float
    {
        return $this->current_value - $this->total_cost;
    }

    /**
     * Get the unrealized gain percentage.
     */
    public function getUnrealizedGainPercentageAttribute(): float
    {
        if ($this->total_cost == 0) {
            return 0;
        }

        return ($this->unrealized_gain / $this->total_cost) * 100;
    }

    /**
     * Get the daily gain/loss.
     */
    public function getDailyGainAttribute(): float
    {
        $currentPrice = $this->asset->latest_price ?? 0;
        $previousPrice = $this->asset->previous_price ?? $currentPrice;
        return $this->quantity * ($currentPrice - $previousPrice);
    }

    /**
     * Check if the position is profitable.
     */
    public function getIsProfitableAttribute(): bool
    {
        return $this->unrealized_gain > 0;
    }

    /**
     * Get the weight in portfolio.
     */
    public function getPortfolioWeightAttribute(): float
    {
        // This would need the total portfolio value from somewhere
        // For simplicity, we'll calculate it on the fly
        $totalValue = Portfolio::where('user_id', $this->user_id)
            ->get()
            ->sum(function($item) {
                $currentPrice = $item->asset->latest_price ?? 0;
                return $item->quantity * $currentPrice;
            });

        if ($totalValue == 0) {
            return 0;
        }

        return ($this->current_value / $totalValue) * 100;
    }

    /**
     * Update portfolio after a trade.
     */
    public static function updateFromTrade(Trade $trade, Order $order): void
    {
        $userId = $order->user_id;
        $assetId = $order->market->base_asset_id; // You'll need to adjust this based on your schema
        $quantity = $trade->quantity;
        $price = $trade->price;

        if ($order->order_type === 'sell') {
            $quantity = -$quantity;
        }

        $portfolio = self::firstOrNew([
            'user_id' => $userId,
            'asset_id' => $assetId
        ]);

        if ($portfolio->exists) {
            // Update existing position with weighted average
            $totalQuantity = $portfolio->quantity + $quantity;
            
            if ($totalQuantity == 0) {
                // Position closed
                $portfolio->delete();
                return;
            }

            $totalCost = ($portfolio->quantity * $portfolio->avg_buy_price) + ($quantity * $price);
            $newAvgPrice = $totalCost / $totalQuantity;

            $portfolio->update([
                'quantity' => $totalQuantity,
                'avg_buy_price' => $newAvgPrice
            ]);
        } else {
            // New position
            if ($quantity > 0) {
                self::create([
                    'user_id' => $userId,
                    'asset_id' => $assetId,
                    'quantity' => $quantity,
                    'avg_buy_price' => $price
                ]);
            }
        }
    }
}