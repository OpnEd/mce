<?php

namespace Database\Factories\Quality\Training;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quality\Training\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'objective' => $this->faker->sentence(8),
            'description' => $this->faker->paragraph(),
            'duration' => $this->faker->numberBetween(1, 40), // horas
            'type' => $this->faker->randomElement(['online', 'presencial', 'mixto']),
            'level' => $this->faker->randomElement(['básico', 'intermedio', 'avanzado']),
            'category' => $this->faker->word(),
            'instructor_id' => \App\Models\User::factory(),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'image' => $this->faker->imageUrl(640, 480, 'education', true),
            'active' => $this->faker->boolean(90),
        ];
    }
}
