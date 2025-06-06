<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Batch;
use App\Models\SanitaryRegistry;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        // Crear 10 registros de SanitaryRegistry
        $sanRegs = [];
        for ($i = 1; $i <= 10; $i++) {
            $sr = SanitaryRegistry::create([
                'code' => Str::upper(Str::random(5)),
                'cum'  => Str::uuid()->toString(),
            ]);
            $sanRegs[] = $sr->id;
        }

        // Crear 20 Batches únicos
        $batchIds = [];
        foreach (range(1, 20) as $index) {
            $batch = Batch::create([
                'team_id'            => 1,
                'sanitary_registry_id' => $sanRegs[array_rand($sanRegs)],
                'manufacturer_id'    => 1,
                'code'               => strtoupper(Str::random(6)),
                'manufacturing_date' => now()->subDays(rand(1, 365)),
                'expiration_date'    => now()->addDays(rand(30, 365)),
                'data'               => null,
            ]);
            $batchIds[] = $batch->id;
        }
        // Obtener IDs de productos y lotes disponibles
        $productIds = Product::pluck('id')->toArray();
        $batchIds = Batch::pluck('id')->toArray();

        // Inicializar seguimiento de lotes asignados por producto
        $assignedBatches = [];
        foreach ($productIds as $pid) {
            $assignedBatches[$pid] = [];
        }

        // 1️⃣ Paso 1: Asegurar al menos 1 registro por producto
        foreach ($productIds as $pid) {
            $available = array_diff($batchIds, $assignedBatches[$pid]);
            if (empty($available)) {
                continue;
            }
            $batchId = Arr::random($available);
            $assignedBatches[$pid][] = $batchId;

            Stock::create([
                'product_id'    => $pid,
                'batch_id'      => $batchId,
                'quantity'      => rand(0, 20),
                'purchase_price'=> rand(100, 10000) / 100,
            ]);
        }

        // 2️⃣ Paso 2: Crear registros adicionales hasta llegar a 30
        $totalToCreate = 30 - count($productIds);
        $created = 0;

        while ($created < $totalToCreate) {
            // Seleccionar producto con menos de 3 lotes asignados
            $eligible = array_filter($assignedBatches, fn($batches) => count($batches) < 3);
            if (empty($eligible)) {
                break;
            }
            $pid = Arr::random(array_keys($eligible));

            // Obtener lotes no asignados aún para ese producto
            $available = array_diff($batchIds, $assignedBatches[$pid]);
            if (empty($available)) {
                // Si no hay lotes nuevos, salts
                unset($eligible[$pid]);
                continue;
            }
            $batchId = Arr::random($available);
            $assignedBatches[$pid][] = $batchId;

            Stock::create([
                'product_id'    => $pid,
                'batch_id'      => $batchId,
                'quantity'      => rand(0, 20),
                'purchase_price'=> rand(100, 10000) / 100,
            ]);

            $created++;
        }
    }
}
