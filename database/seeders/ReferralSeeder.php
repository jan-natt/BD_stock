<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Referral;

class ReferralSeeder extends Seeder
{
    public function run(): void
    {
        Referral::factory()->count(10)->create();
    }
}
