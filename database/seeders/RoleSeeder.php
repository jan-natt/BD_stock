<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'user', 'moderator', 'buyer', 'seller', 'broker', 'investor'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['role_name' => $role]);
        }
    }
}
