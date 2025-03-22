<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Ejecuta la semilla de la tabla.
     *
     * @return void
     */
    public function run()
    {
        // Generamos 10 equipos de prueba
        Team::factory()->count(10)->create();
    }
}
