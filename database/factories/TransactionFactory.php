<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $wallet = Wallet::where('user_id', $user->id)->inRandomOrder()->first() 
                  ?? Wallet::factory()->create(['user_id' => $user->id]);

        $types = ['deposit','withdrawal','trade','fee','referral_bonus','staking_reward'];
        $status = ['pending','completed','failed'];

        return [
            'user_id' => $user->id,
            'wallet_id' => $wallet->id,
            'type' => $this->faker->randomElement($types),
            'amount' => $this->faker->randomFloat(8, 0.01, 10000),
            'fee' => $this->faker->randomFloat(8, 0, 50),
            'status' => $this->faker->randomElement($status),
            'transaction_hash' => $this->faker->uuid(),
        ];
    }
}
