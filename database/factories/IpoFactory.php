<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ipo;
use App\Models\User;

class IpoFactory extends Factory
{
    protected $model = Ipo::class;

    public function definition(): array
    {
        // Issue manager user_id বের করা (যদি না থাকে তাহলে তৈরি করবে)
        $issueManager = User::where('user_type', 'issue_manager')->inRandomOrder()->first()
            ?? User::factory()->create(['user_type' => 'issue_manager']);

        $totalShares = $this->faker->numberBetween(100000, 1000000);

        return [
            'company_name' => $this->faker->company(),
            'symbol' => strtoupper($this->faker->lexify('???')),
            'issue_manager_id' => $issueManager->id,
            'price_per_share' => $this->faker->randomFloat(2, 10, 500),
            'total_shares' => $totalShares,
            'available_shares' => $totalShares,
            'ipo_start' => now()->addDays(rand(1, 5)),
            'ipo_end' => now()->addDays(rand(6, 10)),
            'status' => 'open',
        ];
    }
}
