<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing role-permission relationships
        DB::table('role_permissions')->delete();

        // Get roles and permissions
        $admin = Role::where('role_name', 'admin')->first();
        $buyer = Role::where('role_name', 'buyer')->first();
        $seller = Role::where('role_name', 'seller')->first();
        $issueManager = Role::where('role_name', 'issue_manager')->first();

        $allPermissions = Permission::all();
        $viewDashboard = Permission::where('permission_name', 'view_dashboard')->first();
        $tradeAssets = Permission::where('permission_name', 'trade_assets')->first();
        $createAssets = Permission::where('permission_name', 'create_assets')->first();
        $manageIpo = Permission::where('permission_name', 'manage_ipo')->first();
        $manageUsers = Permission::where('permission_name', 'manage_users')->first();
        $manageRoles = Permission::where('permission_name', 'manage_roles')->first();
        $managePermissions = Permission::where('permission_name', 'manage_permissions')->first();

        // Assign permissions to roles appropriately
        if ($admin && $allPermissions->isNotEmpty()) {
            $admin->permissions()->sync($allPermissions->pluck('id'));
        }

        if ($buyer && $viewDashboard && $tradeAssets) {
            $buyer->permissions()->sync([$viewDashboard->id, $tradeAssets->id]);
        }

        if ($seller && $viewDashboard && $createAssets) {
            $seller->permissions()->sync([$viewDashboard->id, $createAssets->id]);
        }

        if ($issueManager && $viewDashboard && $manageIpo) {
            $issueManager->permissions()->sync([$viewDashboard->id, $manageIpo->id]);
        }

        $this->command->info('Role permissions seeded successfully!');
        $this->command->info('Admin has all permissions');
        $this->command->info('Buyer can view dashboard and trade assets');
        $this->command->info('Seller can view dashboard and create assets');
        $this->command->info('Issue Manager can view dashboard and manage IPOs');
    }
}