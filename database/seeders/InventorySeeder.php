<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Validación previa para evitar errores si no hay datos base
        if (
            Team::count() === 0 ||
            Product::count() === 0 ||
            Batch::count() === 0
        ) {
            $this->command->warn('Faltan datos base en Teams, Products o Batches para ejecutar InventorySeeder.');
            return;
        }

        // Obtener IDs disponibles para relaciones
        $teamIds = Team::pluck('id')->toArray();
        $productIds = Product::pluck('id')->toArray();
        $batchIds = Batch::pluck('id')->toArray();

        for ($i = 0; $i < 10; $i++) {
            $productId = fake()->randomElement($productIds);
            $batchId = fake()->randomElement($batchIds);
            $teamId = fake()->randomElement($teamIds);

            // Obtén la instancia de Product para extraer el nombre
            $product = Product::find($productId);

            Inventory::create([
                'team_id'       => $teamId,
                'product_id'    => $productId,
                'product_name' => $product->name, 
                'batch_id'      => $batchId,
                'quantity'      => fake()->numberBetween(10, 500),
                'purchase_price'=> fake()->randomFloat(2, 100, 5000),
                'created_at'    => now()->subDays(rand(1, 30))->setHour(rand(8, 18))->setMinute(rand(0, 59)),
                'updated_at'    => now(),
            ]);
        }
    }
}
