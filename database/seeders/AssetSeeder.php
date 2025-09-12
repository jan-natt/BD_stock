<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asset;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        // Default কিছু asset create করবো
        $defaultAssets = [
            ['symbol' => 'AAPL', 'name' => 'Apple Inc.', 'type' => 'stock', 'precision' => 2],
            ['symbol' => 'GOOG', 'name' => 'Alphabet Inc.', 'type' => 'stock', 'precision' => 2],
            ['symbol' => 'BTC', 'name' => 'Bitcoin', 'type' => 'crypto', 'precision' => 8],
            ['symbol' => 'ETH', 'name' => 'Ethereum', 'type' => 'crypto', 'precision' => 8],
            ['symbol' => 'USD', 'name' => 'US Dollar', 'type' => 'forex', 'precision' => 4],
        ];

        foreach ($defaultAssets as $asset) {
            Asset::create($asset);
        }

        // Random কিছু extra asset generate করবে
        Asset::factory()->count(10)->create();
    }
}
