<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\RolePermission;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionFactory extends Factory
{
    protected $model = RolePermission::class;

    public function definition(): array
    {
        return [
            'role_id' => Role::inRandomOrder()->first()->id ?? Role::factory()->create()->id,
            'permission_id' => Permission::inRandomOrder()->first()->id ?? Permission::factory()->create()->id,
        ];
    }
}
