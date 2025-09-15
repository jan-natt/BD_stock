<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IPOApplication extends Model
{
    use HasFactory;

    protected $table = 'ipo_applications';

    protected $fillable = [
        'user_id',
        'ipo_id',
        'applied_shares',
        'allocated_shares',
        'total_cost',
        'status',
        'applied_at'
    ];

    protected $casts = [
        'applied_shares' => 'integer',
        'allocated_shares' => 'integer',
        'total_cost' => 'decimal:2',
        'applied_at' => 'datetime'
    ];

    /**
     * Get the user that owns the application.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the IPO that owns the application.
     */
    public function ipo(): BelongsTo
    {
        return $this->belongsTo(IPO::class);
    }

    /**
     * Scope a query to only include pending applications.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include allocated applications.
     */
    public function scopeAllocated($query)
    {
        return $query->where('status', 'allocated');
    }

    /**
     * Scope a query to only include rejected applications.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Check if application is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if application is allocated.
     */
    public function isAllocated(): bool
    {
        return $this->status === 'allocated';
    }

    /**
     * Check if application is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Get the allocation percentage.
     */
    public function getAllocationPercentageAttribute(): float
    {
        if ($this->applied_shares === 0) {
            return 0;
        }

        return ($this->allocated_shares / $this->applied_shares) * 100;
    }

    /**
     * Get the refund amount (if any).
     */
    public function getRefundAmountAttribute(): float
    {
        if (!$this->allocated_shares) {
            return $this->total_cost;
        }

        $unallocatedShares = $this->applied_shares - $this->allocated_shares;
        return $unallocatedShares * $this->ipo->price_per_share;
    }

    /**
     * Get formatted total cost.
     */
    public function getFormattedTotalCostAttribute(): string
    {
        return '$' . number_format($this->total_cost, 2);
    }

    /**
     * Get formatted refund amount.
     */
    public function getFormattedRefundAmountAttribute(): string
    {
        return '$' . number_format($this->refund_amount, 2);
    }

    /**
     * Get days since application.
     */
    public function getDaysSinceApplicationAttribute(): int
    {
        return $this->applied_at->diffInDays(now());
    }

    /**
     * Check if application can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return $this->isPending() && $this->ipo->isActive();
    }
}