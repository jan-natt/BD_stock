<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IPOSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'ipo_id',
        'user_id',
        'shares_subscribed',
        'shares_allocated',
        'amount_paid',
        'status'
    ];

    protected $casts = [
        'shares_subscribed' => 'integer',
        'shares_allocated' => 'integer',
        'amount_paid' => 'decimal:2'
    ];

    /**
     * Get the IPO that owns the subscription.
     */
    public function ipo(): BelongsTo
    {
        return $this->belongsTo(IPO::class);
    }

    /**
     * Get the user that owns the subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active subscriptions.
     */
    public function scopeSubscribed($query)
    {
        return $query->where('status', 'subscribed');
    }

    /**
     * Scope a query to only include allocated subscriptions.
     */
    public function scopeAllocated($query)
    {
        return $query->where('status', 'allocated');
    }

    /**
     * Check if subscription is active.
     */
    public function isSubscribed(): bool
    {
        return $this->status === 'subscribed';
    }

    /**
     * Check if shares have been allocated.
     */
    public function isAllocated(): bool
    {
        return $this->status === 'allocated';
    }

    /**
     * Check if subscription was refunded.
     */
    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    /**
     * Get the allocation percentage.
     */
    public function getAllocationPercentageAttribute(): float
    {
        if ($this->shares_subscribed === 0) {
            return 0;
        }

        return ($this->shares_allocated / $this->shares_subscribed) * 100;
    }

    /**
     * Get the refund amount (if any).
     */
    public function getRefundAmountAttribute(): float
    {
        if (!$this->shares_allocated) {
            return $this->amount_paid;
        }

        $unallocatedShares = $this->shares_subscribed - $this->shares_allocated;
        return $unallocatedShares * $this->ipo->price_per_share;
    }

    /**
     * Get formatted amount paid.
     */
    public function getFormattedAmountPaidAttribute(): string
    {
        return '$' . number_format($this->amount_paid, 2);
    }

    /**
     * Get formatted refund amount.
     */
    public function getFormattedRefundAmountAttribute(): string
    {
        return '$' . number_format($this->refund_amount, 2);
    }
}