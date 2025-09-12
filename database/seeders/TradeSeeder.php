<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trade;

class TradeSeeder extends Seeder
{
    public function run(): void
    {
        Trade::factory()->count(30)->create(); // 30টি ফেইক ট্রেড
    }
}
