<?php

namespace App\Policies;

use App\Models\User;
use App\Models\IPOApplication;
use Illuminate\Auth\Access\Response;

class IPOApplicationPolicy
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
    public function view(User $user, IPOApplication $application): bool
    {
        return $user->id === $application->user_id || 
               $user->user_type === 'admin' || 
               $user->user_type === 'issue_manager';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Any verified user can apply for IPOs
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, IPOApplication $application): bool
    {
        return $user->user_type === 'admin' || $user->user_type === 'issue_manager';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, IPOApplication $application): bool
    {
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can allocate shares.
     */
    public function allocate(User $user, IPOApplication $application): bool
    {
        return $user->user_type === 'admin' || $user->user_type === 'issue_manager';
    }

    /**
     * Determine whether the user can reject applications.
     */
    public function reject(User $user, IPOApplication $application): bool
    {
        return $user->user_type === 'admin' || $user->user_type === 'issue_manager';
    }

    /**
     * Determine whether the user can cancel applications.
     */
    public function cancel(User $user, IPOApplication $application): bool
    {
        return $user->id === $application->user_id && $application->status === 'pending';
    }

    /**
     * Determine whether the user can bulk process applications.
     */
    public function bulkProcess(User $user): bool
    {
        return $user->user_type === 'admin' || $user->user_type === 'issue_manager';
    }

    /**
     * Determine whether the user can export applications.
     */
    public function export(User $user): bool
    {
        return $user->user_type === 'admin' || $user->user_type === 'issue_manager';
    }

    /**
     * Determine whether the user can view statistics.
     */
    public function statistics(User $user): bool
    {
        return $user->user_type === 'admin' || $user->user_type === 'issue_manager';
    }
}