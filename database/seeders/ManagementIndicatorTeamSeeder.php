<?php

namespace Database\Seeders;

use App\Models\ManagementIndicator;
use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManagementIndicatorTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {// Asegúrate de que el equipo 1 existe
        $team = Team::find(1);
        if (! $team) {
            $this->command->warn('No existe el Team con id = 1.');
            return;
        }
        $role = 1;

        // Definimos los nombres de los indicadores según tu array original
        $indicators = [
            'Disponibilidad',
            'Calidad de producto',
            'Cumplimiento Normativo',
            'Satisfacción de los usuarios',
            'Devoluciones',
            'Monitoreo ambiental diario',
            'Monitoreo ambiental mensual',
            'Capacitaciones',
            'Mejora continua',
            'Promoción del uso racional de medicamentos',
            'Errores de dispensación',
            'Recepción técnica',
            'Limpieza y sanitización - Almacenamiento',
            'Limpieza y sanitización - Inyectología',
            'Autoinspecciones',
            'Mantenimiento de equipos',
            'Mantenimiento de instalaciones y enseres',
        ];

        foreach ($indicators as $name) {
            $indicator = ManagementIndicator::where('name', $name)->first();

            if (! $indicator) {
                $this->command->warn("Indicador “{$name}” no encontrado en ManagementIndicator.");
                continue;
            }

            // Conectar sin eliminar posibles existentes
            $team->managementIndicators()
                 ->syncWithoutDetaching([
                     $indicator->id => [
                        'role_id' => $role,
                         // Periodicidad arbitraria; ajústala si necesitas distintos valores
                         'periodicity'    => 'Mensual',
                         // Usamos la meta global como meta personalizada
                         'indicator_goal' => $indicator->indicator_goal,
                         'created_at'     => now(),
                         'updated_at'     => now(),
                     ],
                 ]);

            $this->command->info("Vinculado indicador “{$name}” al Team #1.");
        }
    }
}
