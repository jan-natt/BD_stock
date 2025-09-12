<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wallet;
use App\Models\User;

class WalletSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // প্রতি user কে 1 বা 2 wallet assign
            Wallet::factory()->count(rand(1,2))->create([
                'user_id' => $user->id
            ]);
        }
    }
}
