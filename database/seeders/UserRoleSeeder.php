<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserRole;
use App\Models\User;
use App\Models\Role;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $roles = Role::all();

        foreach ($users as $user) {
            // প্রতিটি user কে random 1 বা 2 role assign করতে পারো
            $assignedRoles = $roles->random(rand(1, 2));

            foreach ($assignedRoles as $role) {
                UserRole::firstOrCreate([
                    'user_id' => $user->id,
                    'role_id' => $role->id,
                ]);
            }
        }

        // Optionally, আরও random assignments factory দিয়ে
        UserRole::factory()->count(10)->create();
    }
}
