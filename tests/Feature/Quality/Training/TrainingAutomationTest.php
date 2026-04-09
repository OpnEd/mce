<?php

namespace Tests\Feature\Quality\Training;

use App\Events\Quality\Training\CourseCreated;
use App\Events\Quality\Training\CourseDeleted;
use App\Events\Quality\Training\CourseUpdated;
use App\Events\Quality\Training\EnrollmentCompleted;
use App\Events\Quality\Training\EnrollmentCreated;
use App\Events\Quality\Training\EnrollmentDeleted;
use App\Events\Quality\Training\EnrollmentUpdated;
use App\Events\Quality\Training\LessonCreated;
use App\Events\Quality\Training\LessonDeleted;
use App\Events\Quality\Training\LessonUpdated;
use App\Events\Quality\Training\ModuleCreated;
use App\Events\Quality\Training\ModuleDeleted;
use App\Events\Quality\Training\ModuleUpdated;
use App\Listeners\Quality\Training\GenerateCertificate;
use App\Listeners\Quality\Training\LogCourseCreated;
use App\Listeners\Quality\Training\LogEnrollmentUpdated;
use App\Listeners\Quality\Training\LogLessonDeleted;
use App\Listeners\Quality\Training\LogModuleCreated;
use App\Models\Quality\Training\Course;
use App\Models\Quality\Training\Enrollment;
use App\Models\Quality\Training\Lesson;
use App\Models\Quality\Training\Module;
use App\Models\User;
use Battery\Tenancy\Tests\Concerns\WithTenants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TrainingAutomationTest extends TestCase
{
    use RefreshDatabase;
    use WithTenants;

    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->student = User::factory()->create();
    }

    public function test_training_listeners_are_registered(): void
    {
        Event::fake();

        Event::assertListening(CourseCreated::class, LogCourseCreated::class);
        Event::assertListening(ModuleCreated::class, LogModuleCreated::class);
        Event::assertListening(EnrollmentUpdated::class, LogEnrollmentUpdated::class);
        Event::assertListening(LessonDeleted::class, LogLessonDeleted::class);
        Event::assertListening(EnrollmentCompleted::class, GenerateCertificate::class);
    }

    public function test_course_observer_dispatches_created_updated_and_deleted_events(): void
    {
        Event::fake([
            CourseCreated::class,
            CourseUpdated::class,
            CourseDeleted::class,
        ]);

        $course = Course::factory()->create([
            'team_id' => $this->tenant->id,
            'instructor_id' => $this->student->id,
            'title' => 'Curso base',
        ]);

        Event::assertDispatched(CourseCreated::class, fn (CourseCreated $event) => $event->course->is($course));

        $course->update([
            'title' => 'Curso actualizado',
            'description' => 'Descripcion nueva',
        ]);

        Event::assertDispatched(CourseUpdated::class, function (CourseUpdated $event) use ($course): bool {
            return $event->course->is($course)
                && ($event->oldValues['title'] ?? null) === 'Curso base'
                && ($event->newValues['title'] ?? null) === 'Curso actualizado'
                && ($event->newValues['description'] ?? null) === 'Descripcion nueva';
        });

        $courseId = $course->id;
        $course->delete();

        Event::assertDispatched(CourseDeleted::class, function (CourseDeleted $event) use ($courseId): bool {
            return $event->courseId === $courseId
                && ($event->courseData['team_id'] ?? null) === $this->tenant->id
                && ($event->courseData['title'] ?? null) === 'Curso actualizado';
        });
    }

    public function test_module_lesson_and_enrollment_observers_dispatch_events_with_context(): void
    {
        Event::fake([
            ModuleCreated::class,
            ModuleUpdated::class,
            ModuleDeleted::class,
            LessonCreated::class,
            LessonUpdated::class,
            LessonDeleted::class,
            EnrollmentCreated::class,
            EnrollmentUpdated::class,
            EnrollmentDeleted::class,
        ]);

        $course = Course::factory()->create([
            'team_id' => $this->tenant->id,
            'instructor_id' => $this->student->id,
            'title' => 'Curso con automatizaciones',
        ]);

        $module = Module::query()->create([
            'course_id' => $course->id,
            'title' => 'Modulo 1',
            'duration' => 30,
            'order' => 1,
            'active' => true,
        ]);

        Event::assertDispatched(ModuleCreated::class, fn (ModuleCreated $event) => $event->module->is($module));

        $module->update(['title' => 'Modulo principal']);

        Event::assertDispatched(ModuleUpdated::class, function (ModuleUpdated $event) use ($module): bool {
            return $event->module->is($module)
                && ($event->oldValues['title'] ?? null) === 'Modulo 1'
                && ($event->newValues['title'] ?? null) === 'Modulo principal';
        });

        $lesson = Lesson::query()->create([
            'module_id' => $module->id,
            'title' => 'Leccion 1',
            'duration' => 20,
            'order' => 1,
            'content' => ['bloque' => 'contenido'],
            'completion_mode' => Lesson::COMPLETION_MODE_CONSUMPTION_ONLY,
            'active' => true,
        ]);

        Event::assertDispatched(LessonCreated::class, fn (LessonCreated $event) => $event->lesson->is($lesson));

        $lesson->update(['title' => 'Leccion inicial']);

        Event::assertDispatched(LessonUpdated::class, function (LessonUpdated $event) use ($lesson): bool {
            return $event->lesson->is($lesson)
                && ($event->oldValues['title'] ?? null) === 'Leccion 1'
                && ($event->newValues['title'] ?? null) === 'Leccion inicial';
        });

        $enrollment = Enrollment::query()->create([
            'team_id' => $this->tenant->id,
            'user_id' => $this->student->id,
            'course_id' => $course->id,
            'status' => Enrollment::STATUS_NOT_STARTED,
            'progress' => 0,
        ]);

        Event::assertDispatched(EnrollmentCreated::class, fn (EnrollmentCreated $event) => $event->enrollment->is($enrollment));

        $enrollment->update([
            'status' => Enrollment::STATUS_IN_PROGRESS,
            'progress' => 50,
        ]);

        Event::assertDispatched(EnrollmentUpdated::class, function (EnrollmentUpdated $event) use ($enrollment): bool {
            return $event->enrollment->is($enrollment)
                && ($event->oldValues['status'] ?? null) === Enrollment::STATUS_NOT_STARTED
                && ($event->newValues['status'] ?? null) === Enrollment::STATUS_IN_PROGRESS
                && ($event->oldValues['progress'] ?? null) === 0
                && ($event->newValues['progress'] ?? null) === 50;
        });

        $lessonId = $lesson->id;
        $lesson->delete();

        Event::assertDispatched(LessonDeleted::class, function (LessonDeleted $event) use ($lessonId): bool {
            return $event->lessonId === $lessonId
                && ($event->lessonData['team_id'] ?? null) === $this->tenant->id
                && ($event->lessonData['title'] ?? null) === 'Leccion inicial';
        });

        $moduleId = $module->id;
        $module->delete();

        Event::assertDispatched(ModuleDeleted::class, function (ModuleDeleted $event) use ($moduleId): bool {
            return $event->moduleId === $moduleId
                && ($event->moduleData['team_id'] ?? null) === $this->tenant->id
                && ($event->moduleData['title'] ?? null) === 'Modulo principal';
        });

        $enrollmentId = $enrollment->id;
        $enrollment->delete();

        Event::assertDispatched(EnrollmentDeleted::class, function (EnrollmentDeleted $event) use ($enrollmentId): bool {
            return $event->enrollmentId === $enrollmentId
                && ($event->enrollmentData['team_id'] ?? null) === $this->tenant->id
                && ($event->enrollmentData['user_name'] ?? null) === $this->student->name;
        });
    }
}
