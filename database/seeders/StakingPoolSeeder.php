<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StakingPool;

class StakingPoolSeeder extends Seeder
{
    public function run(): void
    {
        StakingPool::factory()->count(10)->create();
    }
}
