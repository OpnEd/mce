<?php

namespace Database\Factories;

use App\Models\PharmaceuticalForm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PharmaceuticalForm>
 */
class PharmaceuticalFormFactory extends Factory
{
    protected $model = PharmaceuticalForm::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Tableta',
                'Cápsula',
                'Jarabe',
                'Inyectable',
                'Crema',
                'Supositorio',
                'Suspensión',
                'Parche transdérmico',
                'Solución oftálmica',
                'Aerosol'
            ]),
            'description' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
