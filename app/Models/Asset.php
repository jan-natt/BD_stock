<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol',
        'name',
        'type',
        'precision',
        'status'
    ];

    protected $casts = [
        'precision' => 'integer',
        'status' => 'boolean'
    ];

    /**
     * Get the market data for the asset.
     */
    public function marketData(): HasMany
    {
        return $this->hasMany(MarketData::class);
    }

    /**
     * Get the trades for the asset.
     */
    public function trades(): HasMany
    {
        return $this->hasMany(Trade::class);
    }

    /**
     * Get the wallets for the asset.
     */
    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    /**
     * Scope a query to only include active assets.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to only include specific type of assets.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include assets with symbol like.
     */
    public function scopeSymbolLike($query, $symbol)
    {
        return $query->where('symbol', 'like', "%{$symbol}%");
    }

    /**
     * Scope a query to only include assets with name like.
     */
    public function scopeNameLike($query, $name)
    {
        return $query->where('name', 'like', "%{$name}%");
    }

    /**
     * Check if asset is crypto.
     */
    public function isCrypto(): bool
    {
        return $this->type === 'crypto';
    }

    /**
     * Check if asset is stock.
     */
    public function isStock(): bool
    {
        return $this->type === 'stock';
    }

    /**
     * Check if asset is forex.
     */
    public function isForex(): bool
    {
        return $this->type === 'forex';
    }

    /**
     * Check if asset is commodity.
     */
    public function isCommodity(): bool
    {
        return $this->type === 'commodity';
    }

    /**
     * Check if asset is IPO.
     */
    public function isIpo(): bool
    {
        return $this->type === 'ipo';
    }

    /**
     * Get formatted symbol with name.
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->symbol} - {$this->name}";
    }

  

    /**
     * Get 24h price change.
     */
    public function getPriceChange24hAttribute()
    {
        $current = $this->marketData()->latest()->first();
        $previous = $this->marketData()->where('created_at', '<=', now()->subDay())->latest()->first();

        if (!$current || !$previous) {
            return null;
        }

        return (($current->price - $previous->price) / $previous->price) * 100;
    }





// app/Models/Asset.php
/**
 * Get the price history for the asset.
 */
public function priceHistory(): HasMany
{
    return $this->hasMany(PriceHistory::class);
}

/**
 * Get the latest price from price history.
 */
public function getLatestPriceAttribute()
{
    return $this->priceHistory()
        ->orderBy('timestamp', 'desc')
        ->first()
        ->close ?? 0;
}

/**
 * Get the previous price from price history.
 */
public function getPreviousPriceAttribute()
{
    return $this->priceHistory()
        ->orderBy('timestamp', 'desc')
        ->skip(1)
        ->first()
        ->close ?? $this->latest_price;
}


}