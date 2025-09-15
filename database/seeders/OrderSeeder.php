<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // ৫০টি ফেইক অর্ডার জেনারেট করবো
        Order::factory()->count(10)->create();
    }
}
