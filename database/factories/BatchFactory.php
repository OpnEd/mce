<?php

namespace Database\Factories;

use App\Models\Batch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Batch>
 */
class BatchFactory extends Factory
{
    protected $model = Batch::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'LOTE-' . $this->faker->unique()->bothify('??##??'),
            'expiration_date' => $this->faker->dateTimeBetween('+6 months', '+3 years'),
            'manufacturing_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
