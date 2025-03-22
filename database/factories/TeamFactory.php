<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    /**
     * El nombre del modelo que se relaciona con esta factoría.
     *
     * @var string
     */
    protected $model = Team::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array
     */
    public function definition()
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
