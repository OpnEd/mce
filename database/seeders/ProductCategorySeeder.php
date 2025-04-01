<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductCategory;

class ProductCategorySeeder extends Seeder
{
    /**
     * Ejecuta los seeders para poblar la tabla product_categories.
     *
     * @return void
     */
    public function run()
    {
        // Limpiar la tabla para evitar registros duplicados.
        // IMPORTANTE: Al usar softDeletes, es conveniente truncar la tabla para reiniciar los IDs.
        //ProductCategory::truncate();

        // Definición de categorías con sus subcategorías.
        $categories = [
            [
                'name'        => 'Medicamentos',
                'description' => 'Categoría que agrupa todos los medicamentos.',
                'subcategories' => [
                    [
                        'name'        => 'Antibióticos',
                        'description' => 'Medicamentos para combatir infecciones bacterianas.'
                    ],
                    [
                        'name'        => 'Analgésicos',
                        'description' => 'Medicamentos para el alivio del dolor.'
                    ],
                    [
                        'name'        => 'Antiinflamatorios',
                        'description' => 'Medicamentos para reducir la inflamación.'
                    ],
                    [
                        'name'        => 'Antipiréticos',
                        'description' => 'Medicamentos para controlar la fiebre.'
                    ],
                    [
                        'name'        => 'Controlados',
                        'description' => 'Medicamentos de control especial.'
                    ],
                ],
            ],
            [
                'name'        => 'Reactivos de Diagnóstico',
                'description' => 'Reactivos utilizados en pruebas y análisis de laboratorio.',
                'subcategories' => [
                    [
                        'name'        => 'Hematología',
                        'description' => 'Reactivos para análisis de sangre.'
                    ],
                    [
                        'name'        => 'Bioquímica',
                        'description' => 'Reactivos para pruebas bioquímicas.'
                    ],
                    [
                        'name'        => 'Inmunología',
                        'description' => 'Reactivos para la detección de marcadores inmunológicos.'
                    ],
                    [
                        'name'        => 'Microbiología',
                        'description' => 'Reactivos para el aislamiento y análisis de microorganismos.'
                    ],
                ],
            ],
            [
                'name'        => 'Dispositivos Médicos',
                'description' => 'Equipos e instrumentos utilizados en el ámbito médico.',
                'subcategories' => [
                    [
                        'name'        => 'Equipos de Diagnóstico',
                        'description' => 'Dispositivos para diagnóstico de patologías.'
                    ],
                    [
                        'name'        => 'Equipos Terapéuticos',
                        'description' => 'Dispositivos usados en tratamientos terapéuticos.'
                    ],
                    [
                        'name'        => 'Instrumentos Quirúrgicos',
                        'description' => 'Instrumentos utilizados en procedimientos quirúrgicos.'
                    ],
                    [
                        'name'        => 'Dispositivos de Monitoreo',
                        'description' => 'Equipos para el seguimiento de signos vitales.'
                    ],
                    [
                        'name'        => 'Consumibles Médicos',
                        'description' => 'Material descartable y de un solo uso en entornos clínicos.'
                    ],
                ],
            ],
        ];

        // Insertar cada categoría y luego sus (virtuales) subcategorías.
        foreach ($categories as $categoryData) {
            // Insertar la categoría principal.
            $mainCategory = ProductCategory::create([
                'name' => $categoryData['name'],
                'description' => $categoryData['description'],
            ]);

            // Si existen subcategorías, se insertan como registros independientes.
            if (isset($categoryData['subcategories']) && is_array($categoryData['subcategories'])) {
                foreach ($categoryData['subcategories'] as $subCategoryData) {
                    ProductCategory::create([
                        'name'        => $mainCategory->name . ' - ' . $subCategoryData['name'],
                        'description' => $subCategoryData['description'],
                    ]);
                }
            }
        }
    }
}
