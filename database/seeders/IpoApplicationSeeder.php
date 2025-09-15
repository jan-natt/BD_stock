<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IpoApplication;

class IpoApplicationSeeder extends Seeder
{
    public function run(): void
    {
        IpoApplication::factory()->count(10)->create();
    }
}
