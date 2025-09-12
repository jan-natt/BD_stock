<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KycDocument;
use App\Models\User;

class KycDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all(); // সব ইউজার নাও

        foreach ($users as $user) {
            KycDocument::factory()->count(rand(1, 2))->create([
                'user_id' => $user->id,
                'status' => $user->kyc_status,
                'verified_by' => $user->kyc_status === 'verified' ? 1 : null,
                'verified_at' => $user->kyc_status === 'verified' ? now() : null,
            ]);
        }
    }
}
