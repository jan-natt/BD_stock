<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;
use App\Models\Market;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $orderTypes = ['buy','sell'];
        $orderKinds = ['limit','market','stop-loss','take-profit'];
        $statuses   = ['open','filled','partial','cancelled'];

        $market = Market::inRandomOrder()->first() ?? Market::factory()->create();
        $price  = $this->faker->randomFloat(8, 10, 50000);
        $qty    = $this->faker->randomFloat(8, 0.001, 5);

        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'market_id' => $market->id,
            'order_type' => $this->faker->randomElement($orderTypes),
            'order_kind' => $this->faker->randomElement($orderKinds),
            'price' => $price,
            'quantity' => $qty,
            'filled_quantity' => $this->faker->randomFloat(8, 0, $qty),
            'status' => $this->faker->randomElement($statuses),
        ];
    }
}
