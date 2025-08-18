<?php

namespace Database\Seeders;

use App\Models\Quality\Training\Assessment;
use App\Models\Quality\Training\Lesson;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todas las lecciones que no tienen una evaluación asociada.
        $lessons = Lesson::with('module')->whereDoesntHave('assessment')->get();

        if ($lessons->isEmpty()) {
            $this->command->info('All lessons already have an assessment or no lessons were found.');
            return;
        }

        $this->command->info("Creating assessments for {$lessons->count()} lessons...");

        foreach ($lessons as $lesson) {
            Assessment::updateOrCreate(
                ['lesson_id' => $lesson->id], // Clave única para evitar duplicados
                [
                    'title' => 'Evaluación: ' . $lesson->title,
                    'description' => 'Esta evaluación mide la comprensión de los temas cubiertos en la lección.',
                    'course_id' => $lesson->module->course_id,
                    'module_id' => $lesson->module_id,
                    'type' => 'quiz',
                    'max_score' => 100,
                    'passing_score' => 70,
                    'duration' => 15, // en minutos
                    'active' => true,
                ]
            );
        }

        $this->command->info('Finished creating assessments.');
    }
}
