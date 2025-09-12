<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\KycDocument;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KycDocument>
 */
class KycDocumentFactory extends Factory
{
    protected $model = KycDocument::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'document_type' => $this->faker->randomElement(['passport', 'nid', 'driving_license']),
            'document_file' => $this->faker->imageUrl(400, 300, 'business'),
            'status' => $this->faker->randomElement(['pending', 'verified', 'rejected']),
            'verified_by' => null,
            'verified_at' => null,
        ];
    }
}
