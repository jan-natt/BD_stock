<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Notification;
use App\Models\User;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        return [
            'user_id' => $user->id,
            'title' => $this->faker->sentence(3),
            'message' => $this->faker->paragraph(),
            'is_read' => $this->faker->boolean(50),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
