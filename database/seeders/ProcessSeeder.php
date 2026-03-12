<?php

namespace Database\Seeders;

use App\Models\Process;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $processes = config('processes_templates.default_processes');
        
        
        foreach ($processes as $item) {

            // Si es multi-tenant, agrega aquí el team_id correspondiente
            // Por ejemplo, si quieres plantillas globales, sin team_id, deja solo code
            Process::updateOrCreate(
                [
                    'code' => $item['code'],
                ],
                [
                    'process_type_id' => $item['process_type_id'],
                    'name'            => $item['name'],
                    'description'     => $item['description'],

                    // Estos campos asumo que en la tabla son JSON o text
                    'records'   => $item['records'] ?? [],
                    'suppliers' => $item['suppliers'] ?? [],
                    'inputs'    => $item['inputs'] ?? [],
                    'procedures'=> $item['procedures'] ?? [],
                    'outputs'   => $item['outputs'] ?? [],
                    'clients'   => $item['clients'] ?? [],
                ]
            );
        }

    }
}
