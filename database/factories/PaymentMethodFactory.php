<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PaymentMethod;

class PaymentMethodFactory extends Factory
{
    protected $model = PaymentMethod::class;

    public function definition(): array
    {
        $methods = ['Bank Transfer', 'Bkash', 'Nagad', 'Rocket', 'Paypal', 'Stripe', 'Crypto Wallet'];

        return [
            'method_name' => $this->faker->unique()->randomElement($methods),
            'details' => [
                'account_number' => $this->faker->bankAccountNumber,
                'account_name' => $this->faker->name,
                'extra_note' => $this->faker->sentence,
            ],
            'status' => $this->faker->boolean(90), // 90% chance active
        ];
    }
}
