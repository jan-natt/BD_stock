<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Asset;

class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition(): array
    {
        $types = ['stock','crypto','forex','commodity','ipo'];

        return [
            'symbol' => strtoupper($this->faker->unique()->lexify('???')) . rand(10, 99), // যেমন: ABC42
            'name' => $this->faker->company . ' ' . $this->faker->randomElement(['Ltd','Corp','Inc']),
            'type' => $this->faker->randomElement($types),
            'precision' => $this->faker->randomElement([2, 4, 6, 8]),
            'status' => $this->faker->boolean(90),
        ];
    }
}
