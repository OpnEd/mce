<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CentralProductPrice;

class CentralProductPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar que existen productos
        if (\App\Models\Product::count() === 0) {
            $this->command->error('Primero debes crear los productos!');
            return;
        }

        // Crear un precio central para cada producto existente
        $products = \App\Models\Product::all();

        foreach ($products as $product) {
            CentralProductPrice::create([
            'product_id' => $product->id,
            'min' => rand(1, 20), // Existencias mínimas aleatorias
            'price' => rand(100, 1000) / 10, // Precio aleatorio entre 10.0 y 100.0
            ]);
        }

        $this->command->info('Precios centrales creados para todos los productos existentes!');
    }
}
