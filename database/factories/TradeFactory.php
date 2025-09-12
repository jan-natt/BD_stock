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
        $market = Market::inRandomOrder()->first() ?? Market::factory()->create();

        // একটা buy এবং sell order খুঁজে বের করা
        $buyOrder = Order::where('order_type', 'buy')->inRandomOrder()->first() ?? Order::factory()->create(['order_type' => 'buy']);
        $sellOrder = Order::where('order_type', 'sell')->inRandomOrder()->first() ?? Order::factory()->create(['order_type' => 'sell']);

        $price = $this->faker->randomFloat(8, 10, 50000);
        $quantity = $this->faker->randomFloat(8, 0.001, 2);

        return [
            'buy_order_id' => $buyOrder->id,
            'sell_order_id' => $sellOrder->id,
            'market_id' => $market->id,
            'price' => $price,
            'quantity' => $quantity,
            'fee' => $quantity * $price * 0.001, // 0.1% fee ধরলাম
            'trade_time' => now(),
        ];
    }
}
