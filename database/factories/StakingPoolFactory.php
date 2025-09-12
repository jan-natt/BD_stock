<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StakingPool;

class StakingPoolFactory extends Factory
{
    protected $model = StakingPool::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word . ' Pool',
            'apy' => $this->faker->randomFloat(2, 1, 25), // 1% - 25%
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
