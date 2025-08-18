<?php

namespace Database\Seeders;

use App\Models\ProcessType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProcessTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProcessType::create([
            'name' => 'Planeación y gerencia',
            'code' => 'D',
            'description' => 'Establecen la plataformas estratégica y deontológica de la organización'
        ]);

        ProcessType::create([
            'name' => 'Misonales u Operativos',
            'code' => 'M',
            'description' => 'Crean y desarrollan el producto o el servicio y lo entregan al usuario'
        ]);

        ProcessType::create([
            'name' => 'Apoyo',
            'code' => 'A',
            'description' => 'restan soporte al resto de los procesos para que estos puedan ser realizados efectivamente'
        ]);

        ProcessType::create([
            'name' => 'Evaluación y seguimiento',
            'code' => 'E',
            'description' => 'Miden los resultados de la ejecución de los procesos y analizan estos resultados para generar información que luego es utilizada por los procesos de planeación y gerencia y los procesos evaluados para el desarrollo y la ejecución de planes de mejora continua. Finalmente hacen seguimiento al cumplimiento de planes de mejora.'
        ]);
    }
}
