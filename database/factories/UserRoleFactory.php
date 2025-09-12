<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UserRole;
use App\Models\User;
use App\Models\Role;

class UserRoleFactory extends Factory
{
    protected $model = UserRole::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'role_id' => Role::inRandomOrder()->first()->id ?? Role::factory()->create()->id,
        ];
    }
}
