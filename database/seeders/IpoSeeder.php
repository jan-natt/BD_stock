<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ipo;

class IpoSeeder extends Seeder
{
    public function run(): void
    {
        Ipo::factory()->count(5)->create(); // ৫টা IPO তৈরি করবে
    }
}
