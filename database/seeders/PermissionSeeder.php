<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // কিছু নির্দিষ্ট permission তৈরি
        $permissions = ['create', 'read', 'update', 'delete'];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['permission_name' => $perm]);
        }

        // আরও random permission তৈরি করতে চাইলে factory ব্যবহার করা যায়
        Permission::factory()->count(5)->create();
    }
}
