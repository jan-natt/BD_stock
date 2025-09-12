<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\IpoApplication;
use App\Models\User;
use App\Models\Ipo;

class IpoApplicationFactory extends Factory
{
    protected $model = IpoApplication::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $ipo  = Ipo::inRandomOrder()->first() ?? Ipo::factory()->create();

        $appliedShares = $this->faker->numberBetween(100, 500);
        $totalCost = $ipo->price_per_share * $appliedShares;

        return [
            'user_id' => $user->id,
            'ipo_id' => $ipo->id,
            'applied_shares' => $appliedShares,
            'total_cost' => $totalCost,
            'status' => $this->faker->randomElement(['pending', 'allocated', 'rejected']),
            'applied_at' => now()->subDays(rand(0, 7)),
        ];
    }
}
