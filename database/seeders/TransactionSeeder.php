<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // প্রতি user কে 3-5 transaction assign
            Transaction::factory()->count(rand(3,5))->create([
                'user_id' => $user->id
            ]);
        }
    }
}
