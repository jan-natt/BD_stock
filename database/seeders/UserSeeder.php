<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\KycDocument;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin create (একবারেই তৈরি, যদি আগে থাকে তা ব্যবহার করবে)
        $admin = User::firstOrCreate(
            ['email' => 'admin@yourapp.com'],
            [
                'name' => 'Super Admin',
                'user_type' => 'admin',
                'kyc_status' => 'verified',
                'password' => bcrypt('Admin@123'),
            ]
        );

        // Fake users
        $users = User::factory()->count(10)->create();

        // Fake KYC docs
        foreach ($users as $user) {
            KycDocument::factory()->count(rand(1, 2))->create([
                'user_id' => $user->id,
                'status' => $user->kyc_status,
                'verified_by' => $user->kyc_status === 'verified' ? $admin->id : null,
                'verified_at' => $user->kyc_status === 'verified' ? now() : null,
            ]);
        }
    }
}
