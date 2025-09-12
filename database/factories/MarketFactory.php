<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Market;

class MarketFactory extends Factory
{
    protected $model = Market::class;

    public function definition(): array
    {
        $assets = ['BTC', 'ETH', 'BNB', 'USDT', 'USD', 'AAPL', 'GOOG'];
        $marketTypes = ['spot','margin','futures'];

        $base = $this->faker->randomElement($assets);
        $quote = $this->faker->randomElement(array_diff($assets, [$base]));

        return [
            'base_asset' => $base,
            'quote_asset' => $quote,
            'market_type' => $this->faker->randomElement($marketTypes),
            'min_order_size' => $this->faker->randomFloat(8, 0.001, 1),
            'max_order_size' => $this->faker->randomFloat(8, 5, 100),
            'fee_rate' => $this->faker->randomFloat(2, 0.05, 0.50),
            'status' => $this->faker->boolean(90),
        ];
    }
}
