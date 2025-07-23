<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_category_id' => random_int(1, 17),
            'bar_code' => 'MED-' . $this->faker->unique()->numberBetween(1000, 9999),
            'name' => $this->faker->word,
            'drug' => $this->faker->optional(0.8)->word,
            'drug_concentration' => $this->faker->optional(0.7)->randomFloat(2, 0.01, 100),
            'recommended_dose' => 10,
            'description' => $this->faker->randomElement([
                'Envase con 30 tabletas',
                'Caja con 5 ampollas de 5ml',
                'Tubo con 50g de crema',
                'Frasco de 120ml',
                'Blíster con 10 cápsulas'
            ]),
            'is_high_risk' => $this->faker->boolean(10),
            'is_mce' => $this->faker->boolean(5),
            'fractionable' => $this->faker->boolean(30),
            'conversion_factor' => $this->faker->optional(0.4)->randomFloat(2, 1, 1000),
            'image' => 'products/default.jpg',
            'tax' => $this->faker->randomFloat(2, 0, 21),
            'status' => $this->faker->boolean(85),
            'deleted_at' => null,
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => now(),
            'expediente' => 'expediente-' . $this->faker->unique()->numberBetween(1000, 9999),
            'titular' => $this->faker->company,
            'registro_sanitario' => $this->faker->unique()->numerify('RS-#####'),
            'fecha_expedicion'  => $this->faker->dateTimeBetween('-2 years', 'now'),
            'fecha_vencimiento' => $this->faker->dateTimeBetween('now', '+2 years'),
            'estado_registro'   => $this->faker->randomElement(['vigente', 'vencido']),
            'consecutivo'   => $this->faker->unique()->numberBetween(1000, 9999),
            'cantidad_cum'  => $this->faker->numberBetween(1, 100),
            'estado_cum'    => 1,
            'muestra_medica' => $this->faker->boolean(20),
            'unidad'    => 'a',
            'atc'   => 'A01', // Código ATC genérico
            'descripcion_atc'   => $this->faker->sentence,
            'via_administracion'    => $this->faker->randomElement(['oral', 'iv', 'im']),
            'concentracion' => 1.00,
            'unidad_medida_pa'  => $this->faker->randomElement(['mg', 'g', 'ml']),
            'cantidad'  => $this->faker->numberBetween(1, 100),
            'unidad_referencia' => $this->faker->randomElement(['mg', 'g', 'ml']),
            'forma_farmaceutica'    => $this->faker->randomElement(['Tableta', 'Cápsula', 'Jarabe', 'Crema', 'Solución']),
        ];
    }
}
