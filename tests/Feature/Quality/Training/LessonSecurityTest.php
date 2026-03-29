<?php

namespace Tests\Feature\Quality\Training;

use App\Models\Quality\Training\Course;
use App\Models\Quality\Training\Enrollment;
use App\Models\Quality\Training\Lesson;
use App\Models\Quality\Training\Module;
use App\Models\User;
use Filament\Facades\Filament;
use Tests\TestCase;

class LessonSecurityTest extends TestCase
{
    private User $authorizedUser;
    private User $unauthorizedUser;
    private Enrollment $enrollment;
    private Course $course;
    private Module $module;
    private Lesson $lesson;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear usuarios
        $this->authorizedUser = User::factory()->create();
        $this->unauthorizedUser = User::factory()->create();

        // Crear equipo/tenant
        $team = \App\Models\Team::factory()->create();
        $this->authorizedUser->current_team_id = $team->id;
        $this->authorizedUser->save();
        $this->unauthorizedUser->current_team_id = $team->id;
        $this->unauthorizedUser->save();

        // Crear curso, módulo y lección
        $this->course = Course::factory()->create();
        $this->module = Module::factory()->create(['course_id' => $this->course->id]);
        $this->lesson = Lesson::factory()->create(['module_id' => $this->module->id]);

        // Crear enrollment para usuario autorizado
        $this->enrollment = Enrollment::factory()->create([
            'user_id' => $this->authorizedUser->id,
            'course_id' => $this->course->id,
            'team_id' => $team->id,
        ]);

        // Mock Filament tenant
        Filament::setTenant($team);
    }

    /**
     * Test: Usuario autorizado puede acceder a su lección.
     */
    public function test_authorized_user_can_access_lesson()
    {
        $this->actingAs($this->authorizedUser);

        $response = $this->get(
            route('filament.app.resources.enrollment-resource.lesson', [
                'record' => $this->enrollment->id,
                'lesson' => $this->lesson->id,
            ])
        );

        $response->assertStatus(200);
    }

    /**
     * Test: Usuario no autorizado NO puede acceder a lección de otro.
     */
    public function test_unauthorized_user_cannot_access_lesson()
    {
        $this->actingAs($this->unauthorizedUser);

        $response = $this->get(
            route('filament.app.resources.enrollment-resource.lesson', [
                'record' => $this->enrollment->id,
                'lesson' => $this->lesson->id,
            ])
        );

        $response->assertStatus(403);
    }

    /**
     * Test: Usuario sin inscripción NO puede acceder a lección.
     */
    public function test_user_without_enrollment_cannot_access_lesson()
    {
        // Crear usuario sin enrollment
        $unrelatedUser = User::factory()->create([
            'current_team_id' => $this->authorizedUser->current_team_id,
        ]);

        $this->actingAs($unrelatedUser);

        $response = $this->get(
            route('filament.app.resources.enrollment-resource.lesson', [
                'record' => $this->enrollment->id,
                'lesson' => $this->lesson->id,
            ])
        );

        $response->assertStatus(403);
    }

    /**
     * Test: Usuario no autenticado NO puede acceder a lección.
     */
    public function test_unauthenticated_user_cannot_access_lesson()
    {
        $response = $this->get(
            route('filament.app.resources.enrollment-resource.lesson', [
                'record' => $this->enrollment->id,
                'lesson' => $this->lesson->id,
            ])
        );

        $response->assertStatus(401);
    }

    /**
     * Test: Lección con enrollment incorrecto devuelve 403.
     */
    public function test_lesson_with_wrong_course_returns_forbidden()
    {
        // Crear otro curso y lección
        $otherCourse = Course::factory()->create();
        $otherModule = Module::factory()->create(['course_id' => $otherCourse->id]);
        $otherLesson = Lesson::factory()->create(['module_id' => $otherModule->id]);

        $this->actingAs($this->authorizedUser);

        // Intentar acceder a lección de otro curso con enrollment del primero
        $response = $this->get(
            route('filament.app.resources.enrollment-resource.lesson', [
                'record' => $this->enrollment->id,
                'lesson' => $otherLesson->id,
            ])
        );

        $response->assertStatus(403);
    }
}
