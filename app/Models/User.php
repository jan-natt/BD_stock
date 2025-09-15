<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'user_type',
        'kyc_status',
        'referral_code',
        'referred_by',
        'two_factor_enabled',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for arrays/JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_enabled' => 'boolean',
    ];

    /**
     * Relationships
     */

    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }


    public function isAdmin()
{
    return $this->user_type === 'admin';
}


 public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles')
                    ->withTimestamps();
    }

    /**
     * The permissions that belong to the role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
                    ->withTimestamps();
    }

    /**
     * Check if role has a specific permission.
     */
    public function hasPermission($permissionName): bool
    {
        return $this->permissions()->where('permission_name', $permissionName)->exists();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
                    ->withTimestamps();
    }


    public function hasRole($roleName): bool
    {
        return $this->roles()->where('role_name', $roleName)->exists();
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roleNames): bool
    {
        return $this->roles()->whereIn('role_name', $roleNames)->exists();
    }

    /**
     * Check if user has all of the given roles.
     */
    public function hasAllRoles(array $roleNames): bool
    {
        $count = $this->roles()->whereIn('role_name', $roleNames)->count();
        return $count === count($roleNames);
    }

    /**
     * Get user's permissions through roles.
     */
    public function getPermissionsAttribute()
    {
        return $this->roles->flatMap(function ($role) {
            return $role->permissions;
        })->unique('id');
    }

   
    public function ipoApplications(): HasMany
{
    return $this->hasMany(IPOApplication::class);
}


// app/Models/User.php
/**
 * Get the notifications for the user.
 */
public function notifications(): HasMany
{
    return $this->hasMany(Notification::class);
}

/**
 * Get the unread notifications for the user.
 */
public function unreadNotifications(): HasMany
{
    return $this->notifications()->where('is_read', false);
}

/**
 * Get the read notifications for the user.
 */
public function readNotifications(): HasMany
{
    return $this->notifications()->where('is_read', true);
}


}


