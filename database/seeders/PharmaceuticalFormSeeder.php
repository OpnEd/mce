<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PharmaceuticalForm;

class PharmaceuticalFormSeeder extends Seeder
{
    /**
     * Ejecuta el seeder para poblar la tabla pharmaceutical_forms.
     *
     * @return void
     */
    public function run()
    {
        // Limpiamos la tabla para evitar duplicados
        //PharmaceuticalForm::truncate();

        // Definimos un arreglo con las formas farmacéuticas realistas.
        $forms = [
            [
                'name'        => 'Tableta',
                'description' => 'Forma farmacéutica sólida destinada a la administración oral.'
            ],
            [
                'name'        => 'Cápsula',
                'description' => 'Presentación gelatinosa que contiene el medicamento en su interior.'
            ],
            [
                'name'        => 'Suspensión Oral',
                'description' => 'Preparación líquida en la que los fármacos se dispersan de manera homogénea, utilizada para administración oral.'
            ],
            [
                'name'        => 'Jarabe',
                'description' => 'Preparado líquido, habitualmente aromatizado, para facilitar su administración oral, en especial en pediatría.'
            ],
            [
                'name'        => 'Inyectable',
                'description' => 'Solución o suspensión estéril indicada para la administración parenteral mediante inyección.'
            ],
            [
                'name'        => 'Pomada',
                'description' => 'Preparación semisólida para uso tópico, empleada en tratamientos dermatológicos.'
            ],
            [
                'name'        => 'Supositorio',
                'description' => 'Forma farmacéutica sólida diseñada para la administración rectal o vaginal.'
            ],
            [
                'name'        => 'Ungüento',
                'description' => 'Preparado semisólido similar a la pomada, que se utiliza para aplicación tópica, con una consistencia generalmente más pegajosa.'
            ],
        ];

        // Insertamos cada forma farmacéutica en la base de datos.
        foreach ($forms as $form) {
            PharmaceuticalForm::create($form);
        }
    }
}
