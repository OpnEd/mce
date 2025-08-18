<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Team;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asumiendo que quieres que este documento exista para todos los equipos (tenants)
        $teams = Team::all();

        foreach ($teams as $team) {
            Document::updateOrCreate(
                ['team_id' => $team->id, 'slug' => 'procedimiento-induccion-capacitacion'],
                [
                    'title' => 'Procedimiento de Inducción y Capacitación',
                    // ... Rellena los demás campos necesarios para el documento
                    'document_type_id' => 4, // 'Procedimiento' según tu DocumentCategorySeeder
                    'process_id' => 1, // El ID del proceso al que pertenece
                ]
            );
        }
    }
}
