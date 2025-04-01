<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Batch;
use Carbon\Carbon;

class BatchSeeder extends Seeder
{
    /**
     * Ejecuta el seeder para poblar la tabla batches.
     *
     * @return void
     */
    public function run(): void
    {
        // Limpiamos la tabla para evitar duplicar registros (esto reinicia también los IDs en algunos casos)
        //Batch::truncate();

        // Definición de lotes de ejemplo
        $batches = [
            [
                'team_id' => 1,
                'code' => 'BATCH-2023-001',
                'sanitary_registry_id' => 1,
                'manufacturing_date' => '2023-10-01',
                'expiration_date' => '2025-10-01',
            ],
            [
                'team_id' => 1,
                'code' => 'BATCH-2023-002',
                'sanitary_registry_id' => 1,
                'manufacturing_date' => '2023-10-05',
                'expiration_date' => '2025-10-05',
            ],
            [
                'team_id' => 1,
                'code' => 'BATCH-2023-003',
                'sanitary_registry_id' => 1,
                'manufacturing_date' => '2023-10-10',
                'expiration_date' => '2025-10-10',
            ],
            [
                'team_id' => 1,
                'code' => 'BATCH-2023-004',
                'sanitary_registry_id' => 1,
                'manufacturing_date' => '2023-11-01',
                'expiration_date' => '2025-11-01',
            ],
        ];

        // Insertar los lotes en la base de datos
        foreach ($batches as $batchData) {
            Batch::create($batchData);
        }
    }
}
