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
            //'product_category_id' => \App\Models\ProductCategory::factory(),
            'product_category_id' => random_int(1, 17),
//            'pharmaceutical_form_id' => $this->faker->optional(0.7)->randomElement(\App\Models\PharmaceuticalForm::pluck('id')),
'pharmaceutical_form_id' => random_int(1, 8),
            'code' => 'MED-' . $this->faker->unique()->numberBetween(1000, 9999),
            'name' => function (array $attributes) {
                $form = \App\Models\PharmaceuticalForm::find($attributes['pharmaceutical_form_id']);
                return $this->faker->word . ($form ? ' ' . $form->name : '');
            },
            'drug' => $this->faker->optional(0.8)->word,
            'description' => $this->faker->randomElement([
                'Envase con 30 tabletas',
                'Caja con 5 ampollas de 5ml',
                'Tubo con 50g de crema',
                'Frasco de 120ml',
                'Blíster con 10 cápsulas'
            ]),
            'fractionable' => $this->faker->boolean(30),
            'conversion_factor' => $this->faker->optional(0.4)->randomFloat(2, 1, 1000),
            'image' => 'products/default.jpg',
            'min' => $this->faker->numberBetween(10, 100),
            'tax' => $this->faker->randomFloat(2, 0, 21),
            'status' => $this->faker->boolean(85),
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => now()
        ];
    }
}
