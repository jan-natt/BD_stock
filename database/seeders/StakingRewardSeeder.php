<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StakingReward;

class StakingRewardSeeder extends Seeder
{
    public function run(): void
    {
        StakingReward::factory()->count(10)->create(); // 20 fake rewards
    }
}
