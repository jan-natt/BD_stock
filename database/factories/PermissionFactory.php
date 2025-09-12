<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Permission;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition(): array
    {
        return [
            'permission_name' => $this->faker->unique()->word(), // faker দিয়ে random permission name
        ];
    }
}
