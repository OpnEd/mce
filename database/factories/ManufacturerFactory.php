<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Manufacturer>
 */
class ManufacturerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'           => $this->faker->company,
            'identification' => $this->faker->uuid,
            'address'        => $this->faker->address,
            'email'          => $this->faker->companyEmail,
            'phonenumber'    => $this->faker->phoneNumber,
        ];
    }
}
