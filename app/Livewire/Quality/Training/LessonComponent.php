<?php

namespace App\Livewire\Quality\Training;

use App\Filament\Resources\Quality\Training\EnrollmentResource;
use App\Helpers\Training\BreadcrumbHelper;
use App\Models\Quality\Training\Assessment;
use App\Models\Quality\Training\AssessmentAttempt;
use App\Models\Quality\Training\Enrollment;
use App\Models\Quality\Training\EnrollmentLesson;
use App\Models\Quality\Training\Lesson;
use App\Services\Quality\AssessmentService;
use App\Services\Quality\EnrollmentLessonService;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Livewire\Component;

class LessonComponent extends Component
{
    public $userName;
    public $lessonTitle;
    public $lessonObjective;
    public $lessonDescription;
    public $status;
    public $progress;
    public $started_at;
    public $last_accessed_at;
    public $completed_at;
    public $teamId;
    public $record;
    public $content = [];
    public Enrollment $enrollment;
    public Lesson $lesson;
    public $hasPreviousLesson;
    public $hasNextLesson;
    public $totalLessons;
    public $currentLessonPosition;
    public array $lessonStatus = [];

    public ?Assessment $assessment = null;
    public ?int $remainingAttempts = null;
    public bool $lessonConsumed = false;
    public array $breadcrumbs = [];
    public ?AssessmentAttempt $latestAttempt = null;
    public bool $showAssessment = false;
    public bool $assessmentCanStart = false;
    public ?string $assessmentStartError = null;

    public function mount(Enrollment $enrollment, Lesson $lesson): void
    {
        $this->enrollment = $enrollment->loadMissing('course.modules.lessons');
        $this->lesson = $lesson->load(['module.course', 'assessment.questions.question_options']);

        abort_unless($this->lesson->module->course_id === $this->enrollment->course_id, 404);

        $this->record = $this->lesson;
        $this->teamId = Filament::getTenant()->id;
        $this->updateNavigationState();

        $service = app(EnrollmentLessonService::class);
        $service->touchAccess($service->getOrCreate($this->enrollment, $this->lesson));

        $this->loadLessonStatus();
        $this->loadAssessmentData();
        $this->breadcrumbs = BreadcrumbHelper::getTrainingBreadcrumbs(
            $this->enrollment,
            $this->lesson->module,
            $this->lesson
        );
    }

    public function render()
    {
        return view('livewire.quality.training.lesson-component');
    }

    public function previous(): void
    {
        $previousLesson = $this->getAdjacentLesson(-1);

        if ($previousLesson) {
            $this->redirect(EnrollmentResource::getUrl('lesson', [
                'record' => $this->enrollment,
                'lesson' => $previousLesson,
            ]), navigate: true);
        }
    }

    public function next(): void
    {
        $nextLesson = $this->getAdjacentLesson(1);

        if ($nextLesson) {
            $this->redirect(EnrollmentResource::getUrl('lesson', [
                'record' => $this->enrollment,
                'lesson' => $nextLesson,
            ]), navigate: true);
        }
    }

    protected function loadLessonStatus(): void
    {
        $progress = $this->enrollment->enrollmentLessons()
            ->where('lesson_id', $this->lesson->id)
            ->first();

        $latestAttempt = AssessmentAttempt::query()
            ->where('enrollment_id', $this->enrollment->id)
            ->where('lesson_id', $this->lesson->id)
            ->where('status', 'completed')
            ->latest('id')
            ->first();

        $this->lessonStatus = $this->resolveLessonStatus($progress, $latestAttempt);
    }

    protected function resolveLessonStatus(
        ?EnrollmentLesson $progress,
        ?AssessmentAttempt $latestAttempt
    ): array {
        if (! $progress) {
            return ['text' => 'No cursada', 'color' => 'gray'];
        }

        if ($progress->status === EnrollmentLesson::STATUS_IN_PROGRESS) {
            return ['text' => 'En progreso', 'color' => 'warning'];
        }

        if ($this->lesson->isConsumptionOnly() && in_array($progress->status, [
            EnrollmentLesson::STATUS_CONSUMED,
            EnrollmentLesson::STATUS_PASSED,
        ], true)) {
            return ['text' => 'Vista', 'color' => 'success'];
        }

        if ($progress->status === EnrollmentLesson::STATUS_PASSED || $latestAttempt?->passed) {
            return ['text' => 'Aprobada', 'color' => 'success'];
        }

        if ($progress->status === EnrollmentLesson::STATUS_CONSUMED && $latestAttempt && ! $latestAttempt->passed) {
            return ['text' => 'Reprobada', 'color' => 'danger'];
        }

        if ($progress->status === EnrollmentLesson::STATUS_CONSUMED) {
            return ['text' => 'Pendiente evaluacion', 'color' => 'info'];
        }

        return ['text' => 'No cursada', 'color' => 'gray'];
    }

    protected function loadAssessmentData(): void
    {
        $this->assessment = $this->lesson->assessment;
        $this->assessmentCanStart = false;
        $this->assessmentStartError = null;
        $this->remainingAttempts = null;

        $enrollmentLesson = $this->enrollment->enrollmentLessons()
            ->where('lesson_id', $this->lesson->id)
            ->first();

        $this->lessonConsumed = $enrollmentLesson?->status === EnrollmentLesson::STATUS_CONSUMED
            || $enrollmentLesson?->status === EnrollmentLesson::STATUS_PASSED;

        if (! $this->assessment) {
            $this->assessmentStartError = 'La evaluacion no esta configurada.';
            return;
        }

        $assessmentService = app(AssessmentService::class);

        $this->remainingAttempts = $assessmentService->getRemainingAttempts(
            $this->assessment,
            $this->enrollment,
            auth()->user()
        );

        $this->latestAttempt = AssessmentAttempt::query()
            ->where('assessment_id', $this->assessment->id)
            ->where('enrollment_id', $this->enrollment->id)
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->latest('id')
            ->first();

        if (! $this->lessonConsumed && $this->lesson->requiresAssessment()) {
            $this->assessmentStartError = 'Debes marcar el contenido como revisado antes de evaluar.';
            return;
        }

        [$this->assessmentCanStart, $this->assessmentStartError] = $assessmentService->canStartAttempt(
            $this->assessment,
            $this->enrollment,
            auth()->user()
        );
    }

    protected function getOrderedLessons(): Collection
    {
        return $this->enrollment->course->modules
            ->sortBy('order')
            ->flatMap(fn ($module) => $module->lessons->sortBy('order')->values())
            ->values();
    }

    protected function getAdjacentLesson(int $offset): ?Lesson
    {
        $lessons = $this->getOrderedLessons();
        $currentIndex = $lessons->search(fn (Lesson $lesson) => $lesson->id === $this->lesson->id);

        if ($currentIndex === false) {
            return null;
        }

        return $lessons->get($currentIndex + $offset);
    }

    private function updateNavigationState(): void
    {
        $lessons = $this->getOrderedLessons();
        $currentIndex = $lessons->search(fn (Lesson $lesson) => $lesson->id === $this->lesson->id);

        $this->totalLessons = $lessons->count();
        $this->currentLessonPosition = $currentIndex === false ? 0 : $currentIndex + 1;
        $this->hasPreviousLesson = $currentIndex !== false && $currentIndex > 0;
        $this->hasNextLesson = $currentIndex !== false && $currentIndex < ($lessons->count() - 1);
    }

    #[\Livewire\Attributes\On('markLessonConsumed')]
    public function markLessonConsumed(): void
    {
        $service = app(EnrollmentLessonService::class);
        $enrollmentLesson = $service->getOrCreate($this->enrollment, $this->lesson);
        $service->markConsumed($enrollmentLesson);

        $this->lessonConsumed = true;
        $this->loadLessonStatus();
        $this->loadAssessmentData();

        Notification::make()
            ->title('Leccion marcada como vista')
            ->body('Ahora puedes pasar a la siguiente leccion.')
            ->success()
            ->send();

        $this->dispatch('lesson-consumed', lessonId: $this->lesson->id);
    }

    public function canStartAssessment(): array
    {
        return [$this->assessmentCanStart, $this->assessmentStartError];
    }

    #[\Livewire\Attributes\On('toggleAssessmentForm')]
    public function toggleAssessmentForm(): void
    {
        if (! $this->assessmentCanStart) {
            Notification::make()
                ->title('Evaluacion no disponible')
                ->body($this->assessmentStartError ?? 'No puedes iniciar la evaluacion en este momento.')
                ->warning()
                ->send();

            return;
        }

        $this->showAssessment = ! $this->showAssessment;
    }

    #[\Livewire\Attributes\On('assessment-completed')]
    public function refreshAfterAssessment(): void
    {
        $this->enrollment->refresh();
        $this->showAssessment = false;
        $this->loadLessonStatus();
        $this->loadAssessmentData();
    }
}
