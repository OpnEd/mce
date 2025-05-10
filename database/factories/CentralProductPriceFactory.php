<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CentralProductPrice>
 */
class CentralProductPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => $this->faker->unique()->numberBetween(1, 60), // Usa IDs existentes
            'min' => $this->faker->numberBetween(10, 1000), // Stock mínimo entre 10 y 1000 unidades
            'price' => $this->faker->randomFloat(2, 1, 1000), // Precio entre $1.00 y $1000.00
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => now()
        ];
    }
}
