<?php

namespace Tests\Feature\Quality\Training;

use App\Models\Quality\Training\Assessment;
use App\Models\Quality\Training\AssessmentAttempt;
use App\Models\Quality\Training\Course;
use App\Models\Quality\Training\Enrollment;
use App\Models\Quality\Training\Lesson;
use App\Models\Quality\Training\Module;
use App\Models\User;
use App\Services\Quality\AssessmentService;
use Tests\TestCase;

class AssessmentAttemptLimitTest extends TestCase
{
    private User $user;
    private Enrollment $enrollment;
    private Course $course;
    private Module $module;
    private Lesson $lesson;
    private Assessment $assessment;
    private AssessmentService $assessmentService;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear usuario y team
        $this->user = User::factory()->create();
        $team = \App\Models\Team::factory()->create();
        $this->user->current_team_id = $team->id;
        $this->user->save();

        // Crear estructura de curso
        $this->course = Course::factory()->create();
        $this->module = Module::factory()->create(['course_id' => $this->course->id]);
        $this->lesson = Lesson::factory()->create([
            'module_id' => $this->module->id,
            'completion_mode' => 'assessment_required',
        ]);

        // Crear enrollment
        $this->enrollment = Enrollment::factory()->create([
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'team_id' => $team->id,
        ]);

        // Servicio
        $this->assessmentService = app(AssessmentService::class);
    }

    /**
     * Test: Assessment sin límite de intentos permite intentos ilimitados.
     */
    public function test_assessment_without_max_attempts_allows_unlimited_attempts()
    {
        // Assessment sin límite
        $this->assessment = Assessment::factory()->create([
            'lesson_id' => $this->lesson->id,
            'max_attempts' => null, // ilimitados
            'max_score' => 10,
            'passing_score' => 5,
        ]);

        // Crear múltiples intentos
        for ($i = 0; $i < 5; $i++) {
            $attempt = $this->assessmentService->startAttempt(
                $this->assessment,
                $this->enrollment,
                $this->user
            );
            $this->assertNotNull($attempt->id);
        }

        // Contar intentos
        $totalAttempts = AssessmentAttempt::query()
            ->where('assessment_id', $this->assessment->id)
            ->where('enrollment_id', $this->enrollment->id)
            ->count();

        $this->assertEquals(5, $totalAttempts);
    }

    /**
     * Test: Assessment con límite de 2 intentos bloquea el tercero.
     */
    public function test_assessment_with_max_attempts_blocks_excess_attempts()
    {
        // Assessment con máximo 2 intentos
        $this->assessment = Assessment::factory()->create([
            'lesson_id' => $this->lesson->id,
            'max_attempts' => 2,
            'max_score' => 10,
            'passing_score' => 5,
        ]);

        // Primer intento - debe funcionar
        $attempt1 = $this->assessmentService->startAttempt(
            $this->assessment,
            $this->enrollment,
            $this->user
        );
        $this->assertNotNull($attempt1->id);

        // Segundo intento - debe funcionar
        $attempt2 = $this->assessmentService->startAttempt(
            $this->assessment,
            $this->enrollment,
            $this->user
        );
        $this->assertNotNull($attempt2->id);

        // Tercer intento - debe fallar
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Se ha alcanzado el límite máximo de intentos (2)');

        $this->assessmentService->startAttempt(
            $this->assessment,
            $this->enrollment,
            $this->user
        );
    }

    /**
     * Test: getRemainingAttempts retorna el número correcto.
     */
    public function test_get_remaining_attempts_returns_correct_count()
    {
        // Assessment con máximo 3 intentos
        $this->assessment = Assessment::factory()->create([
            'lesson_id' => $this->lesson->id,
            'max_attempts' => 3,
            'max_score' => 10,
            'passing_score' => 5,
        ]);

        // Verificar intentos al inicio
        $remaining = $this->assessmentService->getRemainingAttempts(
            $this->assessment,
            $this->enrollment,
            $this->user
        );
        $this->assertEquals(3, $remaining);

        // Crear un intento
        $this->assessmentService->startAttempt(
            $this->assessment,
            $this->enrollment,
            $this->user
        );

        // Verificar que redujo a 2
        $remaining = $this->assessmentService->getRemainingAttempts(
            $this->assessment,
            $this->enrollment,
            $this->user
        );
        $this->assertEquals(2, $remaining);

        // Crear otro intento
        $this->assessmentService->startAttempt(
            $this->assessment,
            $this->enrollment,
            $this->user
        );

        // Verificar que redujo a 1
        $remaining = $this->assessmentService->getRemainingAttempts(
            $this->assessment,
            $this->enrollment,
            $this->user
        );
        $this->assertEquals(1, $remaining);
    }

    /**
     * Test: getRemainingAttempts retorna null si es ilimitado.
     */
    public function test_get_remaining_attempts_returns_null_if_unlimited()
    {
        // Assessment sin límite
        $this->assessment = Assessment::factory()->create([
            'lesson_id' => $this->lesson->id,
            'max_attempts' => null,
            'max_score' => 10,
            'passing_score' => 5,
        ]);

        $remaining = $this->assessmentService->getRemainingAttempts(
            $this->assessment,
            $this->enrollment,
            $this->user
        );

        $this->assertNull($remaining);
    }

    /**
     * Test: canStartAttempt verifica correctamente si se puede iniciar.
     */
    public function test_can_start_attempt_validates_correctly()
    {
        // Assessment con máximo 1 intento
        $this->assessment = Assessment::factory()->create([
            'lesson_id' => $this->lesson->id,
            'max_attempts' => 1,
            'max_score' => 10,
            'passing_score' => 5,
        ]);

        // Debe permitir el primer intento
        [$canStart, $error] = $this->assessmentService->canStartAttempt(
            $this->assessment,
            $this->enrollment,
            $this->user
        );
        $this->assertTrue($canStart);
        $this->assertNull($error);

        // Crear el intento
        $this->assessmentService->startAttempt(
            $this->assessment,
            $this->enrollment,
            $this->user
        );

        // Debe bloquear el segundo intento
        [$canStart, $error] = $this->assessmentService->canStartAttempt(
            $this->assessment,
            $this->enrollment,
            $this->user
        );
        $this->assertFalse($canStart);
        $this->assertStringContainsString('límite máximo', $error);
    }

    /**
     * Test: Cada estudiante tiene su propio contador de intentos.
     */
    public function test_each_student_has_separate_attempt_count()
    {
        // Crear otro usuario y enrollment
        $otherUser = User::factory()->create([
            'current_team_id' => $this->user->current_team_id,
        ]);
        $otherEnrollment = Enrollment::factory()->create([
            'user_id' => $otherUser->id,
            'course_id' => $this->course->id,
            'team_id' => $this->enrollment->team_id,
        ]);

        // Assessment con máximo 2 intentos
        $this->assessment = Assessment::factory()->create([
            'lesson_id' => $this->lesson->id,
            'max_attempts' => 2,
            'max_score' => 10,
            'passing_score' => 5,
        ]);

        // Usuario 1 hace 2 intentos
        for ($i = 0; $i < 2; $i++) {
            $this->assessmentService->startAttempt(
                $this->assessment,
                $this->enrollment,
                $this->user
            );
        }

        // Usuario 1 no puede hacer más
        $this->expectException(\RuntimeException::class);
        $this->assessmentService->startAttempt(
            $this->assessment,
            $this->enrollment,
            $this->user
        );
    }

    /**
     * Test: Después de bloquear el tercero, usuario 2 aún puede hacer intento.
     */
    public function test_second_user_can_attempt_after_first_blocked()
    {
        // Crear otro usuario
        $otherUser = User::factory()->create([
            'current_team_id' => $this->user->current_team_id,
        ]);
        $otherEnrollment = Enrollment::factory()->create([
            'user_id' => $otherUser->id,
            'course_id' => $this->course->id,
            'team_id' => $this->enrollment->team_id,
        ]);

        // Assessment con máximo 1 intento
        $this->assessment = Assessment::factory()->create([
            'lesson_id' => $this->lesson->id,
            'max_attempts' => 1,
            'max_score' => 10,
            'passing_score' => 5,
        ]);

        // Usuario 1 hace 1 intento
        $this->assessmentService->startAttempt(
            $this->assessment,
            $this->enrollment,
            $this->user
        );

        // Usuario 2 aún puede hacer intento
        $attempt = $this->assessmentService->startAttempt(
            $this->assessment,
            $this->enrollment,
            $otherUser
        );

        $this->assertNotNull($attempt->id);
        $this->assertEquals($otherUser->id, $attempt->user_id);
    }
}
