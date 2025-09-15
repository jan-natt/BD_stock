<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceHistory extends Model
{
    use HasFactory;

    protected $table = 'price_history';

    protected $fillable = [
        'asset_id',
        'timestamp',
        'open',
        'high',
        'low',
        'close',
        'volume'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'open' => 'decimal:8',
        'high' => 'decimal:8',
        'low' => 'decimal:8',
        'close' => 'decimal:8',
        'volume' => 'decimal:8'
    ];

    /**
     * Get the asset that owns the price history.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Scope a query to only include data for a specific asset.
     */
    public function scopeForAsset($query, $assetId)
    {
        return $query->where('asset_id', $assetId);
    }

    /**
     * Scope a query to only include data within a date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('timestamp', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include recent data.
     */
    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('timestamp', '>=', now()->subHours($hours));
    }

    /**
     * Get the price change from open to close.
     */
    public function getPriceChangeAttribute(): float
    {
        return $this->close - $this->open;
    }

    /**
     * Get the price change percentage.
     */
    public function getPriceChangePercentageAttribute(): float
    {
        if ($this->open == 0) {
            return 0;
        }

        return (($this->close - $this->open) / $this->open) * 100;
    }

    /**
     * Get the high-low range.
     */
    public function getRangeAttribute(): float
    {
        return $this->high - $this->low;
    }

    /**
     * Get the range percentage.
     */
    public function getRangePercentageAttribute(): float
    {
        if ($this->open == 0) {
            return 0;
        }

        return (($this->high - $this->low) / $this->open) * 100;
    }

    /**
     * Check if the period was bullish (close > open).
     */
    public function getIsBullishAttribute(): bool
    {
        return $this->close > $this->open;
    }

    /**
     * Check if the period was bearish (close < open).
     */
    public function getIsBearishAttribute(): bool
    {
        return $this->close < $this->open;
    }

    /**
     * Get the typical price (high + low + close) / 3.
     */
    public function getTypicalPriceAttribute(): float
    {
        return ($this->high + $this->low + $this->close) / 3;
    }

    /**
     * Get the volume-weighted average price.
     */
    public function getVWAPAttribute(): float
    {
        if ($this->volume == 0) {
            return $this->typical_price;
        }

        return $this->typical_price * $this->volume;
    }

    /**
     * Get formatted timestamp for display.
     */
    public function getFormattedTimestampAttribute(): string
    {
        return $this->timestamp->format('Y-m-d H:i:s');
    }

    /**
     * Get the previous period's data.
     */
    public function previousPeriod()
    {
        return self::where('asset_id', $this->asset_id)
            ->where('timestamp', '<', $this->timestamp)
            ->orderBy('timestamp', 'desc')
            ->first();
    }

    /**
     * Get the next period's data.
     */
    public function nextPeriod()
    {
        return self::where('asset_id', $this->asset_id)
            ->where('timestamp', '>', $this->timestamp)
            ->orderBy('timestamp', 'asc')
            ->first();
    }
}