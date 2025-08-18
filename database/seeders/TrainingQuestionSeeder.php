<?php

namespace Database\Seeders;

use App\Models\Quality\Training\Assessment;
use App\Models\Team;
use Illuminate\Database\Seeder;

class TrainingQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asumimos que el Team con ID 1 existe.
        $team = Team::find(47);
        if (!$team) {
            $this->command->error('Team with ID 1 not found. Please seed teams first.');
            return;
        }

        // Obtenemos todas las evaluaciones, asumiendo que ya fueron sembradas.
        $assessments = Assessment::with('lesson.module.course')->whereHas('lesson')->get();

        if ($assessments->isEmpty()) {
            $this->command->warn('No assessments found. Please seed Lessons and Assessments first.');
            return;
        }

        $questionsData = [
            [
                'question_text' => '¿Cuál es el propósito principal del proceso de Selección de medicamentos y dispositivos médicos?',
                'type' => 'multiple_choice_single',
                'options' => [
                    ['option_text' => 'Comprar los productos más baratos.', 'is_correct' => false],
                    ['option_text' => 'Garantizar que los productos disponibles sean seguros, eficaces y costo-efectivos.', 'is_correct' => true],
                    ['option_text' => 'Tener la mayor cantidad de productos posible.', 'is_correct' => false],
                    ['option_text' => 'Vender únicamente marcas reconocidas.', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Seleccione las actividades que forman parte del proceso de Recepción Técnica.',
                'type' => 'multiple_choice_multiple',
                'options' => [
                    ['option_text' => 'Verificar la fecha de vencimiento.', 'is_correct' => true],
                    ['option_text' => 'Negociar el precio con el proveedor.', 'is_correct' => false],
                    ['option_text' => 'Inspeccionar el empaque y etiquetado.', 'is_correct' => true],
                    ['option_text' => 'Consultar el registro sanitario en la página del INVIMA.', 'is_correct' => true],
                ],
            ],
            [
                'question_text' => 'La temperatura ideal para el almacenamiento de la mayoría de los medicamentos es entre 15°C y 25°C.',
                'type' => 'true_false',
                'options' => [
                    ['option_text' => 'Verdadero', 'is_correct' => true],
                    ['option_text' => 'Falso', 'is_correct' => false],
                ],
            ],
            [
                'question_text' => 'Describa brevemente qué es la farmacovigilancia.',
                'type' => 'free_text', // Este tipo no tendrá opciones
                'options' => [],
            ],
        ];

        $this->command->info('Seeding questions and options for existing assessments...');

        foreach ($assessments as $assessment) {
            $this->command->info(" - Seeding for Assessment ID: {$assessment->id} (Lesson: {$assessment->lesson->title})");

            foreach ($questionsData as $qData) {
                $question = $assessment->questions()->create([
                    'team_id' => $team->id,
                    'question_text' => $qData['question_text'],
                    'type' => $qData['type'],
                ]);

                if (!empty($qData['options'])) {
                    $question->question_options()->createMany($qData['options']);
                }
            }
        }

        $this->command->info('Finished seeding questions and options.');
    }
}
