<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['permission_name' => 'view_dashboard', 'category' => 'system', 'description' => 'Access to dashboard'],
            ['permission_name' => 'manage_users', 'category' => 'user', 'description' => 'Manage users'],
            ['permission_name' => 'manage_roles', 'category' => 'role', 'description' => 'Manage roles'],
            ['permission_name' => 'manage_permissions', 'category' => 'permission', 'description' => 'Manage permissions'],
            ['permission_name' => 'trade_assets', 'category' => 'financial', 'description' => 'Trade assets'],
            ['permission_name' => 'create_assets', 'category' => 'financial', 'description' => 'Create assets'],
            ['permission_name' => 'manage_ipo', 'category' => 'financial', 'description' => 'Manage IPOs'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['permission_name' => $permission['permission_name']],
                $permission
            );
        }

        $this->command->info('Permissions seeded successfully!');
    }
}