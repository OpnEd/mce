<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SanitaryRegistry;

class SanitaryRegistrySeeder extends Seeder
{
    /**
     * Ejecuta el seeder para poblar la tabla sanitary_registries.
     *
     * @return void
     */
    public function run()
    {
        // Limpiamos la tabla para evitar duplicados
        //SanitaryRegistry::truncate();

        // Definimos los registros ficticios para el seeder
        $sanitaryRegistries = [
            ['code' => 'REG-001', 'cum' => 'CUM-101'],
            ['code' => 'REG-002', 'cum' => 'CUM-102'],
            ['code' => 'REG-003', 'cum' => 'CUM-103'],
            ['code' => 'REG-004', 'cum' => 'CUM-104'],
            ['code' => 'REG-005', 'cum' => 'CUM-105'],
            ['code' => 'REG-006', 'cum' => 'CUM-106'],
            ['code' => 'REG-007', 'cum' => 'CUM-107'],
            ['code' => 'REG-008', 'cum' => 'CUM-108'],
            ['code' => 'REG-009', 'cum' => 'CUM-109'],
            ['code' => 'REG-010', 'cum' => 'CUM-110'],
        ];

        // Insertamos los registros en la base de datos
        foreach ($sanitaryRegistries as $registry) {
            SanitaryRegistry::create($registry);
        }
    }
}
