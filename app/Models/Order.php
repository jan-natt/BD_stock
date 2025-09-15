<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'market_id',
        'order_type',
        'order_kind',
        'price',
        'quantity',
        'filled_quantity',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:8',
        'quantity' => 'decimal:8',
        'filled_quantity' => 'decimal:8',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the market that owns the order.
     */
    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    /**
     * Get the trades for the order.
     */
    public function trades(): HasMany
    {
        return $this->hasMany(Trade::class);
    }

    /**
     * Scope a query to only include open orders.
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope a query to only include filled orders.
     */
    public function scopeFilled($query)
    {
        return $query->where('status', 'filled');
    }

    /**
     * Scope a query to only include buy orders.
     */
    public function scopeBuy($query)
    {
        return $query->where('order_type', 'buy');
    }

    /**
     * Scope a query to only include sell orders.
     */
    public function scopeSell($query)
    {
        return $query->where('order_type', 'sell');
    }

    /**
     * Scope a query to only include limit orders.
     */
    public function scopeLimit($query)
    {
        return $query->where('order_kind', 'limit');
    }

    /**
     * Scope a query to only include market orders.
     */
    public function scopeMarket($query)
    {
        return $query->where('order_kind', 'market');
    }

    /**
     * Get remaining quantity to be filled.
     */
    public function getRemainingQuantityAttribute(): float
    {
        return $this->quantity - $this->filled_quantity;
    }

    /**
     * Get total value of the order.
     */
    public function getTotalValueAttribute(): ?float
    {
        if (!$this->price) {
            return null; // Market orders don't have a fixed price
        }

        return $this->quantity * $this->price;
    }

    /**
     * Get filled value of the order.
     */
    public function getFilledValueAttribute(): ?float
    {
        if (!$this->price) {
            return null;
        }

        return $this->filled_quantity * $this->price;
    }

    /**
     * Check if order is fully filled.
     */
    public function isFullyFilled(): bool
    {
        return $this->filled_quantity >= $this->quantity;
    }

    /**
     * Check if order is partially filled.
     */
    public function isPartiallyFilled(): bool
    {
        return $this->filled_quantity > 0 && !$this->isFullyFilled();
    }

    /**
     * Check if order is a limit order.
     */
    public function isLimitOrder(): bool
    {
        return $this->order_kind === 'limit';
    }

    /**
     * Check if order is a market order.
     */
    public function isMarketOrder(): bool
    {
        return $this->order_kind === 'market';
    }

    /**
     * Check if order is a stop-loss order.
     */
    public function isStopLossOrder(): bool
    {
        return $this->order_kind === 'stop-loss';
    }

    /**
     * Check if order is a take-profit order.
     */
    public function isTakeProfitOrder(): bool
    {
        return $this->order_kind === 'take-profit';
    }

    /**
     * Check if order can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return $this->status === 'open' && !$this->isFullyFilled();
    }

    /**
     * Get fill percentage.
     */
    public function getFillPercentageAttribute(): float
    {
        if ($this->quantity == 0) {
            return 0;
        }

        return ($this->filled_quantity / $this->quantity) * 100;
    }
}