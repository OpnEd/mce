<?php

namespace App\Livewire\Quality;

use App\Models\Quality\Training\Course;
use App\Models\Quality\Training\Enrollment;
use App\Services\Quality\TrainingService;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CourseList extends Component
{
    /** @var \Illuminate\Support\Collection<int, Course> */
    public $courses;

    public ?int $selectedCourseId = null;

    public $userEnrollments;

    public $teamId;

    protected $training;

    public function mount(TrainingService $training)
    {
        $this->teamId = Filament::getTenant()->id;
        $this->training = $training;
        $this->courses = $this->training->listAvailableCourses($this->teamId);
        $this->userEnrollments = Enrollment::query()
            ->where('user_id', Auth::id())
            ->where('team_id', $this->teamId)
            ->get()
            ->keyBy('course_id');
    }

    public function confirmEnrollment(int $courseId)
    {
        $training = app(TrainingService::class);
        $this->selectedCourseId = $courseId;

        if (! $this->selectedCourseId) {
            Notification::make()
                ->title('Error')
                ->body('No se ha seleccionado ningun curso.')
                ->danger()
                ->send();

            return;
        }

        $teamId = Filament::getTenant()->id;
        $userId = Auth::id();
        $course = Course::query()
            ->visibleToTeam($teamId)
            ->active()
            ->findOrFail($this->selectedCourseId);

        try {
            $enrolled = $training->enroll($teamId, $userId, $courseId);

            Notification::make()
                ->title('Inscripcion exitosa')
                ->body("Te has inscrito en el curso: {$course->title}")
                ->success()
                ->send();

            $this->dispatch('close-modal', id: 'enrollUser');

            return redirect()->route('filament.admin.resources.quality.training.enrollments.view', [
                $teamId,
                $enrolled['enrollment']->id,
            ]);
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Error al inscribirse')
                ->body("No se pudo completar la inscripcion: {$e->getMessage()}")
                ->danger()
                ->send();

            $this->dispatch('close-modal', id: 'enrollUser');
        }
    }

    public function goToEnrollment(int $enrollmentId)
    {
        return redirect()->route('filament.admin.resources.quality.training.enrollments.view', [
            'tenant' => $this->teamId,
            'record' => $enrollmentId,
        ]);
    }

    public function render()
    {
        return view('livewire.quality.course-list');
    }
}
