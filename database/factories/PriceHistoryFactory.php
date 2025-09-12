<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PriceHistory;
use App\Models\Asset;
use Carbon\Carbon;

class PriceHistoryFactory extends Factory
{
    protected $model = PriceHistory::class;

    public function definition(): array
    {
        $asset = Asset::inRandomOrder()->first() ?? Asset::factory()->create();

        // র্যান্ডম প্রাইস জেনারেট
        $open = $this->faker->randomFloat(8, 10, 500);
        $close = $this->faker->randomFloat(8, 10, 500);
        $high = max($open, $close) + $this->faker->randomFloat(8, 0.1, 5);
        $low = min($open, $close) - $this->faker->randomFloat(8, 0.1, 5);

        return [
            'asset_id' => $asset->id,
            'timestamp' => Carbon::now()->subMinutes(rand(1, 10000)),
            'open' => $open,
            'high' => $high,
            'low' => $low,
            'close' => $close,
            'volume' => $this->faker->randomFloat(8, 1, 1000),
        ];
    }
}
