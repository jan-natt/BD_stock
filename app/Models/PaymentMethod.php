<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'method_name',
        'method_type',
        'details',
        'status'
    ];

    protected $casts = [
        'details' => 'array',
        'status' => 'boolean'
    ];

    /**
     * Scope a query to only include active payment methods.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to only include specific type of payment methods.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('method_type', $type);
    }

    /**
     * Check if payment method supports a specific currency.
     */
    public function supportsCurrency($currency): bool
    {
        if (!isset($this->details['currencies']) || empty($this->details['currencies'])) {
            return true; // Supports all currencies if not specified
        }

        return in_array(strtoupper($currency), array_map('strtoupper', $this->details['currencies']));
    }

    /**
     * Calculate fee for a given amount.
     */
    public function calculateFee($amount): float
    {
        $fee = 0;

        if (isset($this->details['fee_percentage'])) {
            $fee += ($amount * $this->details['fee_percentage']) / 100;
        }

        if (isset($this->details['fee_fixed'])) {
            $fee += $this->details['fee_fixed'];
        }

        return max(0, $fee);
    }

    /**
     * Check if amount is within acceptable range.
     */
    public function isAmountWithinLimits($amount): bool
    {
        $min = $this->details['min_amount'] ?? 0;
        $max = $this->details['max_amount'] ?? PHP_FLOAT_MAX;

        return $amount >= $min && ($max === null || $amount <= $max);
    }

    /**
     * Get formatted fee description.
     */
    public function getFeeDescriptionAttribute(): string
    {
        $description = [];

        if (isset($this->details['fee_percentage'])) {
            $description[] = $this->details['fee_percentage'] . '%';
        }

        if (isset($this->details['fee_fixed'])) {
            $description[] = number_format($this->details['fee_fixed'], 2) . ' fixed';
        }

        return $description ? implode(' + ', $description) : 'No fees';
    }

    /**
     * Get supported currencies as string.
     */
    public function getSupportedCurrenciesAttribute(): string
    {
        if (!isset($this->details['currencies']) || empty($this->details['currencies'])) {
            return 'All currencies';
        }

        return implode(', ', $this->details['currencies']);
    }
}