<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Market;

class MarketSeeder extends Seeder
{
    public function run(): void
    {
        // কিছু ডিফল্ট মার্কেট
        $defaultMarkets = [
            ['base_asset' => 'BTC', 'quote_asset' => 'USDT', 'market_type' => 'spot', 'min_order_size' => 0.0001, 'max_order_size' => 10, 'fee_rate' => 0.10],
            ['base_asset' => 'ETH', 'quote_asset' => 'USDT', 'market_type' => 'spot', 'min_order_size' => 0.001, 'max_order_size' => 100, 'fee_rate' => 0.12],
            ['base_asset' => 'AAPL', 'quote_asset' => 'USD', 'market_type' => 'spot', 'min_order_size' => 1, 'max_order_size' => 1000, 'fee_rate' => 0.05],
        ];

        foreach ($defaultMarkets as $market) {
            Market::create($market);
        }

        // র্যান্ডম কিছু মার্কেট
        Market::factory()->count(10)->create();
    }
}
