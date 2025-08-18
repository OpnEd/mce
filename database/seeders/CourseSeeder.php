<?php

namespace Database\Seeders;

use App\Models\Quality\Training\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si el instructor con ID 1 existe.
        $instructor = User::find(1);

        if (!$instructor) {
            $this->command->warn('El instructor con ID 1 no fue encontrado. El curso se creará sin instructor, pero se recomienda crearlo primero.');
            $instructorId = null;
        } else {
            $instructorId = $instructor->id;
        }

        Course::updateOrCreate(
            ['title' => '¿Cómo pasar la visita de la Secretaría de Salud?'],
            [
                'objective' => 'Responder a cada una de las preguntas de la SDS',
                'description' => 'Se muestra cómo responder con la app a cada una de las preguntas de la SDS',
                'duration' => 180,
                'type' => 'virtual',
                'level' => 'avanzado',
                'category' => 'Administración',
                'instructor_id' => $instructorId,
                'price' => 36000,
                'image' => null,
                'active' => true,
            ]
        );

        $this->command->info('Curso "¿Cómo pasar la visita de la Secretaría de Salud?" creado/actualizado exitosamente.');
    }
}
