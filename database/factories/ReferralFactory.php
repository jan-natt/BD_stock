<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Referral;
use App\Models\User;

class ReferralFactory extends Factory
{
    protected $model = Referral::class;

    public function definition(): array
    {
        $referrer = User::inRandomOrder()->first() ?? User::factory()->create();
        $referred = User::inRandomOrder()->first() ?? User::factory()->create();

        // Ensure referrer and referred are not the same
        while ($referrer->id === $referred->id) {
            $referred = User::factory()->create();
        }

        return [
            'referrer_id' => $referrer->id,
            'referred_id' => $referred->id,
            'bonus_amount' => $this->faker->randomFloat(8, 0, 100),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
