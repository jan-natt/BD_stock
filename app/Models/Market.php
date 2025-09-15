<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Market extends Model
{
    use HasFactory;

    protected $fillable = [
        'base_asset',
        'quote_asset',
        'market_type',
        'min_order_size',
        'max_order_size',
        'fee_rate',
        'status'
    ];

    protected $casts = [
        'min_order_size' => 'decimal:8',
        'max_order_size' => 'decimal:8',
        'fee_rate' => 'decimal:2',
        'status' => 'boolean'
    ];

    /**
     * Get the base asset relationship.
     */
    public function baseAsset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'base_asset', 'symbol');
    }

    /**
     * Get the quote asset relationship.
     */
    public function quoteAsset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'quote_asset', 'symbol');
    }

    /**
     * Get the orders for the market.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the trades for the market.
     */
    public function trades(): HasMany
    {
        return $this->hasMany(Trade::class);
    }

    /**
     * Scope a query to only include active markets.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to only include specific type of markets.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('market_type', $type);
    }

    /**
     * Scope a query to only include markets with base asset.
     */
    public function scopeWithBaseAsset($query, $asset)
    {
        return $query->where('base_asset', $asset);
    }

    /**
     * Scope a query to only include markets with quote asset.
     */
    public function scopeWithQuoteAsset($query, $asset)
    {
        return $query->where('quote_asset', $asset);
    }

    /**
     * Get the market symbol (base/quote).
     */
    public function getSymbolAttribute(): string
    {
        return "{$this->base_asset}/{$this->quote_asset}";
    }

    /**
     * Check if market is spot.
     */
    public function isSpot(): bool
    {
        return $this->market_type === 'spot';
    }

    /**
     * Check if market is margin.
     */
    public function isMargin(): bool
    {
        return $this->market_type === 'margin';
    }

    /**
     * Check if market is futures.
     */
    public function isFutures(): bool
    {
        return $this->market_type === 'futures';
    }

    /**
     * Calculate fee for a given order amount.
     */
    public function calculateFee($amount): float
    {
        return ($amount * $this->fee_rate) / 100;
    }

    /**
     * Check if order size is within acceptable range.
     */
    public function isOrderSizeValid($size): bool
    {
        return $size >= $this->min_order_size && 
               ($this->max_order_size == 0 || $size <= $this->max_order_size);
    }

    /**
     * Get formatted fee rate.
     */
    public function getFormattedFeeRateAttribute(): string
    {
        return $this->fee_rate . '%';
    }

    /**
     * Get 24h trading volume.
     */
    public function getDailyVolumeAttribute()
    {
        return $this->trades()
            ->where('created_at', '>=', now()->subDay())
            ->sum('amount');
    }

    /**
     * Get 24h number of trades.
     */
    public function getDailyTradesCountAttribute()
    {
        return $this->trades()
            ->where('created_at', '>=', now()->subDay())
            ->count();
    }
}