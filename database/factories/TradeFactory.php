<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Trade;
use App\Models\Order;
use App\Models\Market;

class TradeFactory extends Factory
{
    protected $model = Trade::class;

    public function definition(): array
    {
        $price = $this->faker->randomFloat(8, 10, 50000);
        $quantity = $this->faker->randomFloat(8, 0.001, 2);

        return [
            'buyer_id' => \App\Models\User::factory(),
            'buy_order_id' => Order::factory()->create(['order_type' => 'buy'])->id,
            'sell_order_id' => Order::factory()->create(['order_type' => 'sell'])->id,
            'market_id' => Market::factory(),
            'price' => $price,
            'quantity' => $quantity,
            'fee' => $quantity * $price * 0.001, // 0.1% fee ধরলাম
            'trade_time' => now(),
        ];
    }
}
