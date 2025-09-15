<?php

namespace App\Policies;

use App\Models\User;
use App\Models\IPO;
use Illuminate\Auth\Access\Response;

class IPOPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->user_type === 'admin' || $user->user_type === 'issue_manager';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, IPO $ipo): bool
    {
        return true; // IPOs are public, but some details might be restricted
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->user_type === 'admin' || $user->user_type === 'issue_manager';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, IPO $ipo): bool
    {
        return $user->user_type === 'admin' || 
               ($user->user_type === 'issue_manager' && $user->id === $ipo->issue_manager_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, IPO $ipo): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can subscribe to IPO.
     */
    public function subscribe(User $user, IPO $ipo): bool
    {
        return $ipo->status === 'open' && 
               now() >= $ipo->ipo_start && 
               now() <= $ipo->ipo_end;
    }

    /**
     * Determine whether the user can close IPO.
     */
    public function close(User $user, IPO $ipo): bool
    {
        return ($user->user_type === 'admin' || 
               ($user->user_type === 'issue_manager' && $user->id === $ipo->issue_manager_id)) &&
               $ipo->status === 'open' &&
               now() >= $ipo->ipo_end;
    }

    /**
     * Determine whether the user can cancel IPO.
     */
    public function cancel(User $user, IPO $ipo): bool
    {
        return $user->user_type === 'admin' || 
               ($user->user_type === 'issue_manager' && $user->id === $ipo->issue_manager_id);
    }

    /**
     * Determine whether the user can export IPO data.
     */
    public function export(User $user, IPO $ipo): bool
    {
        return $user->user_type === 'admin' || 
               ($user->user_type === 'issue_manager' && $user->id === $ipo->issue_manager_id);
    }

    /**
     * Determine whether the user can view statistics.
     */
    public function statistics(User $user): bool
    {
        return $user->user_type === 'admin' || $user->user_type === 'issue_manager';
    }
}