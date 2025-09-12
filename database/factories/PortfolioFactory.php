<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Portfolio;
use App\Models\User;
use App\Models\Asset;

class PortfolioFactory extends Factory
{
    protected $model = Portfolio::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $asset = Asset::inRandomOrder()->first() ?? Asset::factory()->create();

        $quantity = $this->faker->randomFloat(8, 0.1, 50);
        $price = $this->faker->randomFloat(8, 10, 500);

        return [
            'user_id' => $user->id,
            'asset_id' => $asset->id,
            'quantity' => $quantity,
            'avg_buy_price' => $price,
        ];
    }
}
