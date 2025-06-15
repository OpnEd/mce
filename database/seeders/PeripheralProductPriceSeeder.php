<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\PeripheralProductPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeripheralProductPriceSeeder extends Seeder
{
    public function run(): void
    {
        $teamId = 14;

        // Obtener 10 productos aleatorios existentes
        $products = Product::inRandomOrder()->limit(10)->get();

        foreach ($products as $product) {
            PeripheralProductPrice::create([
                'team_id'     => $teamId,
                'product_id'  => $product->id,
                'min_stock'   => rand(5, 50),
                'sale_price'  => rand(1000, 10000) / 100, // Ej: 23.75
            ]);
        }
    }
}
