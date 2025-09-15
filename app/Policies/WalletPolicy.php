<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Access\Response;

class WalletPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Wallet $wallet): bool
    {
        return $user->id === $wallet->user_id || $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create wallets
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Wallet $wallet): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Wallet $wallet): bool
    {
        return $user->id === $wallet->user_id && $wallet->balance == 0;
    }

    /**
     * Determine whether the user can perform admin actions.
     */
    public function adminActions(User $user, Wallet $wallet): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can transfer from the wallet.
     */
    public function transfer(User $user, Wallet $wallet): bool
    {
        return $user->id === $wallet->user_id && !$wallet->is_locked;
    }
}