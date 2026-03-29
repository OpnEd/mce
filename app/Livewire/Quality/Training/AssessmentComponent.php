<?php

namespace App\Livewire\Quality\Training;

use App\Models\Quality\Training\Assessment;
use App\Models\Quality\Training\AssessmentAttempt;
use App\Models\Quality\Training\Enrollment;
use App\Services\Quality\AssessmentService;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Livewire\Component;

class AssessmentComponent extends Component
{
    public Assessment $assessment;

    public Enrollment $enrollment;

    public ?AssessmentAttempt $currentAttempt = null;

    public array $userAnswers = [];

    public bool $isSubmitting = false;

    public bool $showResults = false;

    public ?array $results = null;

    protected AssessmentService $assessmentService;

    public function mount(Assessment $assessment, Enrollment $enrollment): void
    {
        $this->assessment = $assessment;
        $this->enrollment = $enrollment;
        $this->assessmentService = app(AssessmentService::class);

        // Start the attempt
        $this->startAttempt();
    }

    /**
     * Start a new assessment attempt
     */
    public function startAttempt(): void
    {
        try {
            $this->currentAttempt = $this->assessmentService->startAttempt(
                assessment: $this->assessment,
                enrollment: $this->enrollment,
                user: auth()->user(),
            );

            $this->userAnswers = [];
            $this->showResults = false;
            $this->results = null;

            Notification::make()
                ->title('Evaluación iniciada')
                ->body('Tienes ' . ($this->assessment->duration_minutes ?? 'sin límite') . ' minutos para completarla.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->currentAttempt = null;
        }
    }

    /**
     * Submit the assessment
     */
    public function submitAssessment(): void
    {
        if (!$this->currentAttempt) {
            return;
        }

        $this->isSubmitting = true;

        try {
            // Validate that all questions are answered
            $requiredQuestions = $this->assessment->questions()
                ->where('required', true)
                ->pluck('id')
                ->toArray();

            $unansweredRequired = array_diff($requiredQuestions, array_keys($this->userAnswers));

            if (!empty($unansweredRequired)) {
                Notification::make()
                    ->title('Validación')
                    ->body('Debes responder todas las preguntas obligatorias.')
                    ->warning()
                    ->send();

                $this->isSubmitting = false;

                return;
            }

            // Save answers and grade the attempt
            $this->currentAttempt = $this->assessmentService->submitAttempt(
                attempt: $this->currentAttempt,
                answers: $this->userAnswers,
            );

            // Grade the attempt
            $this->currentAttempt = $this->assessmentService->gradeAttempt($this->currentAttempt);

            // Calculate results
            $this->results = [
                'score' => $this->currentAttempt->score ?? 0,
                'passed' => $this->currentAttempt->isPassed(),
                'total_questions' => $this->assessment->questions()->count(),
                'correct_answers' => $this->calculateCorrectAnswers(),
                'duration' => $this->currentAttempt->durationInMinutes(),
                'feedback' => $this->assessment->show_feedback ? $this->generateFeedback() : null,
            ];

            $this->showResults = true;

            // Notify success
            Notification::make()
                ->title('Evaluación completada')
                ->body('Tu resultado es: ' . number_format($this->results['score'], 1) . '%')
                ->success()
                ->send();

            // Dispatch event to refresh parent component
            $this->dispatch('assessment-completed', [
                'attempt_id' => $this->currentAttempt->id,
                'score' => $this->currentAttempt->score,
                'passed' => $this->currentAttempt->isPassed(),
            ]);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Error al procesar la evaluación: ' . $e->getMessage())
                ->danger()
                ->send();
        } finally {
            $this->isSubmitting = false;
        }
    }

    /**
     * Calculate number of correct answers
     */
    private function calculateCorrectAnswers(): int
    {
        $correct = 0;

        foreach ($this->userAnswers as $questionId => $answer) {
            $question = $this->assessment->questions()->find($questionId);

            if ($question && $this->isAnswerCorrect($question, $answer)) {
                $correct++;
            }
        }

        return $correct;
    }

    /**
     * Check if an answer is correct
     */
    private function isAnswerCorrect($question, $answer): bool
    {
        // Multiple choice
        if ($question->type === 'multiple_choice') {
            return $question->correct_answer === $answer;
        }

        // True/False
        if ($question->type === 'true_false') {
            return strtolower($question->correct_answer) === strtolower($answer);
        }

        // Short answer (case-insensitive)
        if ($question->type === 'short_answer') {
            return strtolower(trim($question->correct_answer)) === strtolower(trim($answer));
        }

        return false;
    }

    /**
     * Generate feedback based on performance
     */
    private function generateFeedback(): string
    {
        $score = $this->results['score'] ?? 0;

        if ($score >= 90) {
            return '¡Excelente desempeño! Demostraste dominio del tema.';
        } elseif ($score >= 80) {
            return 'Buen trabajo. Considera repasar los temas donde fallaste.';
        } elseif ($score >= 70) {
            return 'Acertado. Te recomendamos estudiar más a fondo el material.';
        } else {
            return 'Necesitas reforzar estos temas. Te sugerimos repasar el material y intentar de nuevo.';
        }
    }

    /**
     * Cancel the attempt
     */
    public function cancelAttempt(): void
    {
        if ($this->currentAttempt && !$this->currentAttempt->isCompleted()) {
            $this->currentAttempt->delete();
        }

        $this->currentAttempt = null;
        $this->userAnswers = [];
        $this->showResults = false;

        Notification::make()
            ->title('Evaluación cancelada')
            ->body('Tu intento ha sido cancelado.')
            ->info()
            ->send();
    }

    public function render()
    {
        return view('livewire.quality.training.assessment-component', [
            'questions' => $this->assessment->questions()->get(),
            'questionsCount' => $this->assessment->questions()->count(),
        ]);
    }
}
