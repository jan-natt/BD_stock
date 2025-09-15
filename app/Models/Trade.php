<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trade extends Model
{
    use HasFactory;

    protected $fillable = [
        'buy_order_id',
        'sell_order_id',
           'buyer_id',       // Add this
    'seller_id',
        'market_id',
        'price',
        'quantity',
        'fee',
        'trade_time'
    ];

    protected $casts = [
        'price' => 'decimal:8',
        'quantity' => 'decimal:8',
        'fee' => 'decimal:8',
        'trade_time' => 'datetime'
    ];

    /**
     * Get the buy order that owns the trade.
     */
    public function buyOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'buy_order_id');
    }

    /**
     * Get the sell order that owns the trade.
     */
    public function sellOrder(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'sell_order_id');
    }

    /**
     * Get the market that owns the trade.
     */
    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    /**
     * Scope a query to only include trades for a specific market.
     */
    public function scopeForMarket($query, $marketId)
    {
        return $query->where('market_id', $marketId);
    }

    /**
     * Scope a query to only include trades within a date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('trade_time', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include trades involving a specific user.
     */
    public function scopeInvolvingUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->whereHas('buyOrder', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->orWhereHas('sellOrder', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });
        });
    }

    /**
     * Get the total value of the trade.
     */
    public function getTotalValueAttribute(): float
    {
        return $this->price * $this->quantity;
    }

    /**
     * Get the fee percentage.
     */
    public function getFeePercentageAttribute(): float
    {
        if ($this->total_value == 0) {
            return 0;
        }

        return ($this->fee / $this->total_value) * 100;
    }

    /**
     * Check if user participated in the trade.
     */
    public function involvesUser($userId): bool
    {
        return $this->buyOrder->user_id === $userId || 
               $this->sellOrder->user_id === $userId;
    }

    /**
     * Get user's side in the trade.
     */
    public function getUserSide($userId): ?string
    {
        if ($this->buyOrder->user_id === $userId) {
            return 'buy';
        }

        if ($this->sellOrder->user_id === $userId) {
            return 'sell';
        }

        return null;
    }

    /**
     * Get user's counterparty in the trade.
     */
    public function getCounterparty($userId): ?User
    {
        if ($this->buyOrder->user_id === $userId) {
            return $this->sellOrder->user;
        }

        if ($this->sellOrder->user_id === $userId) {
            return $this->buyOrder->user;
        }

        return null;
    }

    /**
     * Get formatted trade time.
     */
    public function getFormattedTradeTimeAttribute(): string
    {
        return $this->trade_time->format('Y-m-d H:i:s');
    }

    /**
     * Get trade details for display.
     */
    public function getTradeDetailsAttribute(): array
    {
        return [
            'id' => $this->id,
            'market' => $this->market->symbol,
            'price' => (float)$this->price,
            'quantity' => (float)$this->quantity,
            'total' => (float)$this->total_value,
            'fee' => (float)$this->fee,
            'trade_time' => $this->formatted_trade_time,
            'buyer' => $this->buyOrder->user->name,
            'seller' => $this->sellOrder->user->name,
        ];
    }
}