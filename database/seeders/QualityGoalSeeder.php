<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QualityGoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indicators = [
            // 1
            [
                'name' => 'Disponibilidad',
                'description' => 'Garantizar el abastecimiento oportuno y la disponibilidad de productos. ',
                'data' => [],
            ],

            // 2
            [
                'name' => 'Calidad de los Productos',
                'description' => 'Garantizar que todos los productos sean auténticos y estén certificados por el INVIMA. Mantener registros actualizados de proveedores confiables y verificar la calidad de los productos recibidos.',
                'data' => [],
            ],

            // 3
            [
                'name' => 'Seguridad y Eficacia de los Medicamentos',
                'description' => 'Cumplir con los estándares de almacenamiento adecuado para preservar la efectividad de los medicamentos. Capacitar al personal en el manejo seguro de medicamentos y en la correcta dispensación según las indicaciones médicas.',
                'data' => [],
            ],

            // 4
            [
                'name' => 'Cumplimiento Normativo y Legal',
                'description' => 'Garantizar el cumplimiento de todas las regulaciones locales, regionales y nacionales relacionadas con la venta de productos farmacéuticos. Realizar auditorías internas periódicas para asegurar el cumplimiento de los estándares de calidad y legalidad.',
                'data' => [],
            ],

            // 5
            [
                'name' => 'Satisfacción de los usuarios',
                'description' => 'Proporcionar un servicio al cliente excepcional, brindando información precisa y consejos sobre los productos disponibles. Mantener un ambiente limpio, ordenado y acogedor para garantizar una experiencia de compra positiva.',
                'data' => [],
            ],

            // 6
            [
                'name' => 'Gestión Eficiente del Inventario',
                'description' => 'Implementar sistemas de gestión de inventario efectivos para asegurar el abastecimiento oportuno y la disponibilidad de productos. Minimizar el desperdicio y las pérdidas mediante un control riguroso del inventario y la rotación adecuada de productos.',
                'data' => [],
            ],

            // 7
            [
                'name' => 'Formación y Desarrollo del Personal',
                'description' => 'Proporcionar formación continua al personal sobre los productos, las regulaciones y las mejores prácticas de atención al cliente. Fomentar un ambiente de trabajo colaborativo y motivador que impulse el compromiso y la excelencia en el servicio.',
                'data' => [],
            ],

            // 8
            [
                'name' => 'Mejora Continua',
                'description' => 'Evaluar la satisfacción de los usuarios para identificar áreas de mejora y oportunidades de crecimiento.Implementar medidas correctivas y preventivas para abordar cualquier problema identificado a través de cualquier medio (encuestas, auditorías internas y externas, etc.) y optimizar los procesos internos.',
                'data' => [],
            ],

            // 9
            [
                'name' => 'Responsabilidad Ambiental y Social',
                'description' => 'Adoptar prácticas comerciales sostenibles, como el uso de empaques ecoamigables y la gestión adecuada de residuos. Contribuir activamente a la comunidad local mediante iniciativas de responsabilidad social corporativa, como programas de educación sobre salud y bienestar.',
                'data' => [],
            ],
        ];
        foreach ($indicators as $i) {
            \App\Models\QualityGoal::updateOrCreate(
                //
                [
                    'name' => $i['name'],
                    'description' => $i['description'],
                    'data' => [],
                ]
            );
        }
    }
}
