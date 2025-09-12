<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StakingReward;
use App\Models\User;
use App\Models\StakingPool;

class StakingRewardFactory extends Factory
{
    protected $model = StakingReward::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'staking_pool_id' => StakingPool::inRandomOrder()->first()->id,
            'reward_amount' => $this->faker->randomFloat(8, 0.01, 50), // 0.01 - 50
            'distributed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
