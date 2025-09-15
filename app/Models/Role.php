<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['role_name'];

   
    public function permissions(): BelongsToMany
    {
       return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }

   
    public function hasPermission($permissionName): bool
    {
        return $this->permissions()->where('permission_name', $permissionName)->exists();
    }

      public function users(): BelongsToMany
    {
       return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }

   
}