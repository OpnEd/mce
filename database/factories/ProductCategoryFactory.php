<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductCategory>
 */
class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Antibióticos',
                'Analgésicos',
                'Antiinflamatorios',
                'Antivirales',
                'Hormonas',
                'Vacunas',
                'Oftalmológicos',
                'Dermatológicos',
                'Cardiovasculares',
                'Oncológicos'
            ]),
            'description' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
