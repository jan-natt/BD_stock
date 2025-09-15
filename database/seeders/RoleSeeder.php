<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'user', 'moderator', 'buyer', 'seller', 'broker', 'investor'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(
                ['role_name' => $roleName],
                ['role_name' => $roleName]
            );
        }

        $this->command->info('Roles seeded successfully!');
    }
}