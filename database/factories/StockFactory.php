<?php

namespace Database\Factories;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 */
class StockFactory extends Factory
{
    protected $model = Stock::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            //'batch_id' => \App\Models\Batch::factory(),
            'batch_id' => random_int(1, 4),
            'quantity' => $this->faker->numberBetween(50, 1000),
            'purchase_price' => $this->faker->randomFloat(2, 5, 500),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now()
        ];
    }
}
