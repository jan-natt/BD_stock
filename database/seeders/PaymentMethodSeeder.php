<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        // Default কিছু payment method বানাবো
        $defaultMethods = [
            [
                'method_name' => 'Bank Transfer',
                'details' => ['bank_name' => 'City Bank', 'account_number' => '1234567890'],
                'status' => true,
            ],
            [
                'method_name' => 'Bkash',
                'details' => ['merchant_number' => '017XXXXXXXX'],
                'status' => true,
            ],
            [
                'method_name' => 'Crypto Wallet',
                'details' => ['wallet_address' => '0x' . substr(md5(rand()), 0, 32)],
                'status' => true,
            ],
        ];

        foreach ($defaultMethods as $method) {
            PaymentMethod::create($method);
        }

        // Extra fake methods
        PaymentMethod::factory()->count(3)->create();
    }
}
