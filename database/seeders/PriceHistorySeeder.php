<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PriceHistory;

class PriceHistorySeeder extends Seeder
{
    public function run(): void
    {
        PriceHistory::factory()->count(10)->create(); // 100 টা রেকর্ড
    }
}
