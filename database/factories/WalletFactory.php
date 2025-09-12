<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Wallet;
use App\Models\User;

class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition(): array
    {
        $currencies = ['USD', 'BTC', 'ETH', 'EUR'];

        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'currency' => $this->faker->randomElement($currencies),
            'balance' => $this->faker->randomFloat(8, 0, 10000),
            'is_locked' => $this->faker->boolean(10), // 10% chance locked
        ];
    }
}
