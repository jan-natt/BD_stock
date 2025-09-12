<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RolePermission;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::all();
        $permissions = Permission::all();

        foreach ($roles as $role) {
            // প্রতিটি role কে সব permissions assign
            foreach ($permissions as $permission) {
                RolePermission::firstOrCreate([
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                ]);
            }
        }

        // Optionally, আরও random assignments করতে পারো
        RolePermission::factory()->count(10)->create();
    }
}
