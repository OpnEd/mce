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

        // Crear precios para todos los productos existentes
        CentralProductPrice::factory(60)->create();

        $this->command->info('Precios centrales creados para 60 productos existentes!');
    }
}
