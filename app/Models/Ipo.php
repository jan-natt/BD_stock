<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IPO extends Model
{
    use HasFactory;

       protected $table = 'ipos';

    protected $fillable = [
        'company_name',
        'symbol',
        'issue_manager_id',
        'price_per_share',
        'total_shares',
        'available_shares',
        'ipo_start',
        'ipo_end',
        'status',
        'description',
        'min_subscription',
        'max_subscription'
    ];

    protected $casts = [
        'price_per_share' => 'decimal:2',
        'total_shares' => 'integer',
        'available_shares' => 'integer',
        'ipo_start' => 'datetime',
        'ipo_end' => 'datetime',
        'min_subscription' => 'integer',
        'max_subscription' => 'integer'
    ];

    /**
     * Get the issue manager that owns the IPO.
     */
    public function issueManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issue_manager_id');
    }

    /**
     * Get the subscriptions for the IPO.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(IPOSubscription::class, 'ipo_id');
    }

    /**
     * Scope a query to only include open IPOs.
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope a query to only include active IPOs (within subscription period).
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'open')
            ->where('ipo_start', '<=', now())
            ->where('ipo_end', '>=', now());
    }

    /**
     * Scope a query to only include upcoming IPOs.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'open')
            ->where('ipo_start', '>', now());
    }

    /**
     * Scope a query to only include closed IPOs.
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    /**
     * Check if IPO is currently active.
     */
    public function isActive(): bool
    {
        return $this->status === 'open' &&
               now() >= $this->ipo_start &&
               now() <= $this->ipo_end;
    }

    /**
     * Check if IPO is upcoming.
     */
    public function isUpcoming(): bool
    {
        return $this->status === 'open' && now() < $this->ipo_start;
    }

    /**
     * Check if IPO has ended.
     */
    public function hasEnded(): bool
    {
        return now() > $this->ipo_end;
    }

    /**
     * Get the total capital raised.
     */
    public function getCapitalRaisedAttribute(): float
    {
        return $this->subscriptions()->sum('amount_paid');
    }

    /**
     * Get the subscription progress percentage.
     */
    public function getSubscriptionProgressAttribute(): float
    {
        if ($this->total_shares === 0) {
            return 0;
        }

        $subscribedShares = $this->total_shares - $this->available_shares;
        return ($subscribedShares / $this->total_shares) * 100;
    }

    /**
     * Get the number of investors.
     */
    public function getInvestorCountAttribute(): int
    {
        return $this->subscriptions()->count();
    }

    /**
     * Get the days remaining until IPO ends.
     */
    public function getDaysRemainingAttribute(): int
    {
        if (now() > $this->ipo_end) {
            return 0;
        }

        return now()->diffInDays($this->ipo_end);
    }

    /**
     * Get formatted price per share.
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price_per_share, 2);
    }

    /**
     * Get formatted total value.
     */
    public function getFormattedTotalValueAttribute(): string
    {
        return '$' . number_format($this->total_shares * $this->price_per_share, 2);
    }

    /**
     * Get formatted capital raised.
     */
    public function getFormattedCapitalRaisedAttribute(): string
    {
        return '$' . number_format($this->capital_raised, 2);
    }


    public function applications(): HasMany
{
    return $this->hasMany(IPOApplication::class);
}
}
