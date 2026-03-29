<?php

namespace App\Filament\Resources\Quality\Training\EnrollmentResource\Pages;

use App\Filament\Resources\Quality\Training\EnrollmentResource;
use App\Models\Quality\Training\Enrollment;
use App\Models\Quality\Training\EnrollmentLesson;
use App\Models\Quality\Training\Lesson;
use App\Models\Quality\Training\UserAnswer;
use App\Services\Quality\AssessmentService;
use App\Services\Quality\EnrollmentLessonService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class Lessonview extends ViewRecord
{
    use InteractsWithRecord;

    protected static string $resource = EnrollmentResource::class;

    protected static string $view = 'filament.pages.quality.lesson-view';

    public Lesson $lesson;

    public function mount(int | string $record): void
    {
        $lesson = request()->route('lesson');

        $this->record = $this->resolveRecord($record);
        
        // SEGURIDAD: Validar que el usuario actual es el propietario del enrollment
        $this->authorizeEnrollmentAccess($this->record);
        
        $this->lesson = Lesson::query()
            ->with(['module.course', 'assessment.questions.question_options'])
            ->findOrFail($lesson);

        // SEGURIDAD: Validar que la lección pertenece al curso del enrollment
        abort_unless($this->lesson->module->course_id === $this->record->course_id, 403);
    }

    /**
     * Validar que el usuario actual tiene permiso para acceder a este enrollment y lección.
     */
    public function authorizeEnrollmentAccess(Enrollment $enrollment): void
    {
        $user = Auth::user();
        $tenant = Filament::getTenant();

        // Validar que el usuario existe y está autenticado
        abort_unless($user, 401, 'Usuario no autenticado');

        // Validar que el enrollment existe
        abort_unless($enrollment, 404, 'Matricula no encontrada');

        // Validar que el usuario actual es el propietario del enrollment
        abort_unless(
            $enrollment->user_id === $user->id,
            403,
            'No tienes permiso para acceder a esta matrícula'
        );

        // Validar que el enrollment pertenece al tenant actual
        abort_unless(
            $enrollment->team_id === $tenant?->id,
            403,
            'Matricula no valida para este equipo'
        );
    }

    public function getLesson(): Lesson
    {
        return $this->lesson;
    }

    protected function getHeaderActions(): array
    {
        $actions = [
            Action::make('volver')
                ->label('Volver al curso')
                ->icon('heroicon-o-arrow-left')
                ->url(fn (): string => EnrollmentResource::getUrl('view', ['record' => $this->record])),
        ];

        $service = app(EnrollmentLessonService::class);
        $enrollmentLesson = $service->getOrCreate($this->record, $this->lesson);

        if ($this->lesson->isConsumptionOnly() && ! $enrollmentLesson->isResolved()) {
            $actions[] = Action::make('markViewed')
                ->label('Marcar como vista')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function () use ($service): void {
                    $enrollmentLesson = $service->getOrCreate($this->record, $this->lesson);
                    $service->markConsumed($enrollmentLesson);

                    Notification::make()
                        ->title('Leccion marcada como vista')
                        ->success()
                        ->send();

                    $this->redirect(EnrollmentResource::getUrl('lesson', [
                        'record' => $this->record,
                        'lesson' => $this->lesson,
                    ]), navigate: true);
                });
        }

        if (! $this->lesson->requiresAssessment()) {
            return $actions;
        }

        if (! in_array($enrollmentLesson->status, [
            EnrollmentLesson::STATUS_CONSUMED,
            EnrollmentLesson::STATUS_PASSED,
        ], true)) {
            $actions[] = Action::make('markContentReviewed')
                ->label('Marcar contenido revisado')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->action(function () use ($service): void {
                    $enrollmentLesson = $service->getOrCreate($this->record, $this->lesson);
                    $service->markConsumed($enrollmentLesson);

                    Notification::make()
                        ->title('Contenido marcado como revisado')
                        ->body('Ahora puedes presentar la evaluacion de la leccion.')
                        ->success()
                        ->send();

                    $this->redirect(EnrollmentResource::getUrl('lesson', [
                        'record' => $this->record,
                        'lesson' => $this->lesson,
                    ]), navigate: true);
                });
        }

        $assessment = $this->lesson->assessment;

        if (! $assessment) {
            $actions[] = Action::make('assessmentUnavailable')
                ->label('Evaluacion no configurada')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('gray')
                ->disabled();

            return $actions;
        }

        if (! in_array($enrollmentLesson->status, [
            EnrollmentLesson::STATUS_CONSUMED,
            EnrollmentLesson::STATUS_PASSED,
        ], true)) {
            return $actions;
        }

        // Obtener información sobre intentos restantes
        $assessmentService = app(AssessmentService::class);
        $remainingAttempts = $assessmentService->getRemainingAttempts(
            $assessment,
            $this->record,
            auth()->user()
        );

        // Construir etiqueta con información de intentos
        $label = 'Presentar evaluacion';
        if ($assessment->max_attempts !== null) {
            $label .= $remainingAttempts === 0
                ? ' (Ya no hay intentos)'
                : " ({$remainingAttempts} intento" . ($remainingAttempts === 1 ? '' : 's') . " restante" . ($remainingAttempts === 1 ? '' : 's') . ')';
        }

        // Si no hay intentos restantes, desabilitar la acción
        if ($remainingAttempts === 0) {
            $actions[] = Action::make('takeAssessmentDisabled')
                ->label($label)
                ->icon('heroicon-o-academic-cap')
                ->color('danger')
                ->disabled();

            return $actions;
        }

        $actions[] = Action::make('takeAssessment')
            ->label($label)
            ->icon('heroicon-o-academic-cap')
            ->color('warning')
            ->form(function () use ($assessment, $remainingAttempts) {
                $formSchema = [];

                // Mostrar advertencia si es el último intento
                if ($remainingAttempts === 1) {
                    $formSchema[] = Placeholder::make('last_attempt_warning')
                        ->content('⚠️ Este es tu **último intento**. Asegúrate de estar listo.')
                        ->hiddenLabel();
                }

                foreach ($assessment->questions as $question) {
                    $options = $question->question_options->pluck('option_text', 'id')->toArray();

                    switch ($question->type) {
                        case 'multiple_choice_single':
                            $formSchema[] = Radio::make('answers.' . $question->id)
                                ->label($question->question_text)
                                ->options($options)
                                ->required();
                            break;
                        case 'multiple_choice_multiple':
                            $formSchema[] = CheckboxList::make('answers.' . $question->id)
                                ->label($question->question_text)
                                ->options($options)
                                ->required();
                            break;
                        case 'free_text':
                            $formSchema[] = Textarea::make('answers.' . $question->id)
                                ->label($question->question_text)
                                ->required();
                            break;
                        case 'true_false':
                            $formSchema[] = Radio::make('answers.' . $question->id)
                                ->label($question->question_text)
                                ->options($options)
                                ->required();
                            break;
                    }
                }

                return $formSchema;
            })
            ->action(function (array $data) use ($assessment): void {
                $user = auth()->user();
                $assessmentService = app(AssessmentService::class);

                $attempt = $assessmentService->startAttempt($assessment, $this->record, $user);

                foreach (($data['answers'] ?? []) as $questionId => $userAnswer) {
                    if (is_array($userAnswer)) {
                        foreach ($userAnswer as $optionId) {
                            UserAnswer::create([
                                'user_id' => $user->id,
                                'question_id' => $questionId,
                                'question_option_id' => $optionId,
                                'assessment_attempt_id' => $attempt->id,
                            ]);
                        }

                        continue;
                    }

                    if (is_numeric($userAnswer)) {
                        UserAnswer::create([
                            'user_id' => $user->id,
                            'question_id' => $questionId,
                            'question_option_id' => $userAnswer,
                            'assessment_attempt_id' => $attempt->id,
                        ]);
                    }
                }

                $attempt = $assessmentService->gradeAttempt($attempt, $data['answers'] ?? []);

                Notification::make()
                    ->title($attempt->passed ? 'Evaluacion aprobada' : 'Evaluacion no aprobada')
                    ->body("Tu puntaje fue {$attempt->score}/{$assessment->max_score}.")
                    ->color($attempt->passed ? 'success' : 'danger')
                    ->send();

                $this->redirect(EnrollmentResource::getUrl('lesson', [
                    'record' => $this->record,
                    'lesson' => $this->lesson,
                ]), navigate: true);
            })
            ->modalHeading('Evaluacion: ' . $assessment->title)
            ->modalSubmitActionLabel('Enviar respuestas');

        return $actions;
    }
}
