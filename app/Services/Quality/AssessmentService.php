<?php

namespace App\Services\Quality;

use App\Models\Quality\Training\Assessment;
use App\Models\Quality\Training\AssessmentAttempt;
use App\Models\Quality\Training\Enrollment;
use App\Models\Quality\Training\EnrollmentLesson;
use App\Models\Quality\Training\Lesson;
use Illuminate\Support\Facades\DB;

class AssessmentService
{
    public function __construct(
        protected EnrollmentLessonService $enrollmentLessons
    ) {}

    public function startAttempt(Assessment $assessment, Enrollment $enrollment, $user): AssessmentAttempt
    {
        // SEGURIDAD: Validar que el usuario pertenece al enrollment
        if ($user->id !== $enrollment->user_id) {
            throw new \RuntimeException('El usuario no está inscrito en esta matrícula.');
        }

        $lesson = $assessment->lesson;

        // VALIDACIÓN: El assessment debe estar asociado a una lección
        if (! $lesson) {
            throw new \RuntimeException('El assessment no está asociado a una lección.');
        }

        // SEGURIDAD: Validar que el assessment pertenece al curso del enrollment
        if ($lesson->module->course_id !== $enrollment->course_id) {
            throw new \RuntimeException('La matrícula no corresponde al curso del assessment.');
        }

        // VALIDACIÓN: Verificar límite de intentos si está configurado
        $maxAttempts = $assessment->max_attempts;
        if ($maxAttempts !== null && $maxAttempts > 0) {
            $attemptCount = AssessmentAttempt::query()
                ->where('assessment_id', $assessment->id)
                ->where('enrollment_id', $enrollment->id)
                ->where('user_id', $user->id)
                ->count();

            if ($attemptCount >= $maxAttempts) {
                throw new \RuntimeException(
                    "Se ha alcanzado el límite máximo de intentos ({$maxAttempts}) para esta evaluación."
                );
            }
        }

        return DB::transaction(function () use ($assessment, $enrollment, $lesson, $user) {
            $enrollmentLesson = $this->enrollmentLessons->getOrCreate($enrollment, $lesson);
            $this->enrollmentLessons->touchAccess($enrollmentLesson);

            return AssessmentAttempt::create([
                'assessment_id' => $assessment->id,
                'enrollment_id' => $enrollment->id,
                'lesson_id' => $lesson->id,
                'user_id' => $user->id,
                'status' => 'in_progress',
                'started_at' => now(),
                'passed' => false,
            ]);
        });
    }

    /**
     * Save user answers to an attempt without grading yet
     */
    public function submitAttempt(AssessmentAttempt $attempt, array $answers): AssessmentAttempt
    {
        // Validate that this attempt belongs to the current user
        if ($attempt->user_id !== auth()->id()) {
            throw new \RuntimeException('No tienes permiso para enviar esta evaluación.');
        }

        // Validate attempt is still in progress
        if ($attempt->status !== 'in_progress') {
            throw new \RuntimeException('Este intento ya ha sido completado.');
        }

        // Save the responses (answers will be graded in next step)
        $attempt->responses = $answers;
        $attempt->save();

        return $attempt->fresh();
    }

    /**
     * Grade an assessment attempt
     */
    public function gradeAttempt(AssessmentAttempt $attempt): AssessmentAttempt
    {
        return DB::transaction(function () use ($attempt) {
            $assessment = $attempt->assessment()->with('questions', 'lesson')->firstOrFail();
            $lesson = $attempt->lesson ?? $assessment->lesson;

            if (! $lesson) {
                throw new \RuntimeException('No se pudo resolver la lección del intento.');
            }

            // Get answers from the attempt
            $answers = $attempt->responses ?? [];

            // Calculate score
            $questions = $assessment->questions()->get();
            $totalQuestions = $questions->count();
            $correctAnswers = 0;

            foreach ($questions as $question) {
                $userAnswer = $answers[$question->id] ?? null;

                if ($this->isAnswerCorrect($question, $userAnswer)) {
                    $correctAnswers++;
                }
            }

            // Calculate percentage score
            $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
            $passed = $score >= ($assessment->passing_score ?? 60);

            // Update attempt with results
            $attempt->status = 'completed';
            $attempt->completed_at = now();
            $attempt->score = round($score, 2);
            $attempt->passed = $passed;
            $attempt->passed_at = $passed ? now() : null;
            $attempt->lesson_id ??= $lesson->id;
            $attempt->save();

            // Update enrollment lesson status
            if ($attempt->enrollment) {
                $enrollmentLesson = $this->enrollmentLessons->getOrCreate($attempt->enrollment, $lesson);

                if ($passed) {
                    $this->enrollmentLessons->markPassed($enrollmentLesson, $attempt);
                } else {
                    $this->enrollmentLessons->markConsumed($enrollmentLesson);
                }
            }

            return $attempt->fresh();
        });
    }

    /**
     * Check if an answer is correct
     */
    private function isAnswerCorrect($question, $answer): bool
    {
        if (is_null($answer) || $answer === '') {
            return false;
        }

        // Multiple choice
        if ($question->type === 'multiple_choice') {
            return $question->correct_answer === $answer;
        }

        // True/False
        if ($question->type === 'true_false') {
            return strtolower((string) $question->correct_answer) === strtolower((string) $answer);
        }

        // Short answer (case-insensitive)
        if ($question->type === 'short_answer') {
            return strtolower(trim((string) $question->correct_answer)) === strtolower(trim((string) $answer));
        }

        // Essay - always considered for manual review (return true for now)
        if ($question->type === 'essay') {
            return true;
        }

        return false;
    }

    /**
     * Obtener el número de intentos restantes para una evaluación.
     * Retorna null si es ilimitado.
     */
    public function getRemainingAttempts(Assessment $assessment, Enrollment $enrollment, $user): ?int
    {
        if ($assessment->max_attempts === null) {
            return null; // Ilimitados
        }

        $usedAttempts = AssessmentAttempt::query()
            ->where('assessment_id', $assessment->id)
            ->where('enrollment_id', $enrollment->id)
            ->where('user_id', $user->id)
            ->count();

        return max(0, $assessment->max_attempts - $usedAttempts);
    }

    /**
     * Verificar si un usuario puede iniciar un intento de la evaluación.
     * Retorna [bool, ?string] - (can_attempt, error_message)
     */
    public function canStartAttempt(Assessment $assessment, Enrollment $enrollment, $user): array
    {
        // Validar pertenencia
        if ($user->id !== $enrollment->user_id) {
            return [false, 'El usuario no está inscrito en esta matrícula.'];
        }

        // Validar que assessment tiene lección
        if (! $assessment->lesson) {
            return [false, 'El assessment no está asociado a una lección.'];
        }

        // Validar que assessment pertenece al curso
        if ($assessment->lesson->module->course_id !== $enrollment->course_id) {
            return [false, 'La matrícula no corresponde al curso del assessment.'];
        }

        // Validar límite de intentos
        if ($assessment->max_attempts !== null && $assessment->max_attempts > 0) {
            $remainingAttempts = $this->getRemainingAttempts($assessment, $enrollment, $user);
            if ($remainingAttempts === 0) {
                return [false, "Se ha alcanzado el límite máximo de intentos ({$assessment->max_attempts}) para esta evaluación."];
            }
        }

        return [true, null];
    }
}
