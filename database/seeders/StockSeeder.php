<?php

namespace Database\Seeders;

use App\Models\Stock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar que existan productos y lotes
        /* if (\App\Models\Product::count() === 0 || \App\Models\Batch::count() === 0) {
            $this->call([
                ProductSeeder::class, // Asegurar productos
                BatchSeeder::class    // Asegurar lotes
            ]);
        } */

        // Crear 30 registros de stock
        Stock::factory(30)->create();
    }
}
