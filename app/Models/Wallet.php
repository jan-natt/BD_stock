<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'currency',
        'balance',
        'is_locked'
    ];

    protected $casts = [
        'balance' => 'decimal:8',
        'is_locked' => 'boolean',
    ];

    /**
     * Get the user that owns the wallet.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transactions for the wallet.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Scope a query to only include unlocked wallets.
     */
    public function scopeUnlocked($query)
    {
        return $query->where('is_locked', false);
    }

    /**
     * Scope a query to only include locked wallets.
     */
    public function scopeLocked($query)
    {
        return $query->where('is_locked', true);
    }

    /**
     * Scope a query to only include wallets with balance.
     */
    public function scopeWithBalance($query)
    {
        return $query->where('balance', '>', 0);
    }

    /**
     * Check if wallet has sufficient balance.
     */
    public function hasSufficientBalance($amount): bool
    {
        return $this->balance >= $amount;
    }

    /**
     * Deposit funds into wallet.
     */
    public function deposit($amount): void
    {
        if ($this->is_locked) {
            throw new \Exception('Wallet is locked');
        }

        $this->increment('balance', $amount);
    }

    /**
     * Withdraw funds from wallet.
     */
    public function withdraw($amount): void
    {
        if ($this->is_locked) {
            throw new \Exception('Wallet is locked');
        }

        if (!$this->hasSufficientBalance($amount)) {
            throw new \Exception('Insufficient balance');
        }

        $this->decrement('balance', $amount);
    }
}