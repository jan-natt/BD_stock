<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AuditLog;
use App\Models\User;

class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        return [
            'user_id' => $this->faker->boolean(80) ? $user->id : null, // কখনো null থাকবে
            'action' => $this->faker->randomElement([
                'login', 'logout', 'create_order', 'update_profile', 'delete_account'
            ]),
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
