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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssessmentAttemptLimitTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Enrollment $enrollment;
    private Course $course;
    private Lesson $lesson;
    private AssessmentService $assessmentService;
    private \App\Models\Team $team;

    protected function setUp(): void
    {
        parent::setUp();

        $this->team = \App\Models\Team::factory()->create();
        $this->user = User::factory()->create([
            'current_team_id' => $this->team->id,
        ]);

        $this->course = Course::factory()->create([
            'team_id' => $this->team->id,
        ]);

        $module = Module::create([
            'course_id' => $this->course->id,
            'title' => 'Modulo 1',
            'objective' => 'Objetivo',
            'description' => 'Descripcion',
            'duration' => 60,
            'order' => 1,
            'active' => true,
        ]);

        $this->lesson = Lesson::create([
            'module_id' => $module->id,
            'title' => 'Leccion 1',
            'objective' => 'Objetivo',
            'description' => 'Descripcion',
            'duration' => 15,
            'order' => 1,
            'content' => 'Contenido',
            'active' => true,
            'completion_mode' => Lesson::COMPLETION_MODE_ASSESSMENT_REQUIRED,
        ]);

        $this->enrollment = Enrollment::create([
            'team_id' => $this->team->id,
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'status' => Enrollment::STATUS_IN_PROGRESS,
            'progress' => 0,
        ]);

        $this->assessmentService = app(AssessmentService::class);
    }

    public function test_assessment_without_max_attempts_allows_unlimited_attempts(): void
    {
        $assessment = $this->createAssessment(maxAttempts: null);

        for ($i = 0; $i < 5; $i++) {
            $attempt = $this->assessmentService->startAttempt(
                $assessment,
                $this->enrollment,
                $this->user
            );

            $this->assertNotNull($attempt->id);
        }

        $totalAttempts = AssessmentAttempt::query()
            ->where('assessment_id', $assessment->id)
            ->where('enrollment_id', $this->enrollment->id)
            ->count();

        $this->assertEquals(5, $totalAttempts);
    }

    public function test_assessment_with_max_attempts_blocks_excess_attempts(): void
    {
        $assessment = $this->createAssessment(maxAttempts: 2);

        $attempt1 = $this->assessmentService->startAttempt($assessment, $this->enrollment, $this->user);
        $attempt2 = $this->assessmentService->startAttempt($assessment, $this->enrollment, $this->user);

        $this->assertNotNull($attempt1->id);
        $this->assertNotNull($attempt2->id);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Se ha alcanzado el limite maximo de intentos (2)');

        $this->assessmentService->startAttempt($assessment, $this->enrollment, $this->user);
    }

    public function test_get_remaining_attempts_returns_correct_count(): void
    {
        $assessment = $this->createAssessment(maxAttempts: 3);

        $this->assertEquals(3, $this->assessmentService->getRemainingAttempts($assessment, $this->enrollment, $this->user));

        $this->assessmentService->startAttempt($assessment, $this->enrollment, $this->user);
        $this->assertEquals(2, $this->assessmentService->getRemainingAttempts($assessment, $this->enrollment, $this->user));

        $this->assessmentService->startAttempt($assessment, $this->enrollment, $this->user);
        $this->assertEquals(1, $this->assessmentService->getRemainingAttempts($assessment, $this->enrollment, $this->user));
    }

    public function test_get_remaining_attempts_returns_null_if_unlimited(): void
    {
        $assessment = $this->createAssessment(maxAttempts: null);

        $remaining = $this->assessmentService->getRemainingAttempts($assessment, $this->enrollment, $this->user);

        $this->assertNull($remaining);
    }

    public function test_can_start_attempt_validates_correctly(): void
    {
        $assessment = $this->createAssessment(maxAttempts: 1);

        [$canStart, $error] = $this->assessmentService->canStartAttempt(
            $assessment,
            $this->enrollment,
            $this->user
        );

        $this->assertTrue($canStart);
        $this->assertNull($error);

        $this->assessmentService->startAttempt($assessment, $this->enrollment, $this->user);

        [$canStart, $error] = $this->assessmentService->canStartAttempt(
            $assessment,
            $this->enrollment,
            $this->user
        );

        $this->assertFalse($canStart);
        $this->assertStringContainsString('limite maximo', $error);
    }

    public function test_each_student_has_separate_attempt_count(): void
    {
        $otherUser = User::factory()->create([
            'current_team_id' => $this->team->id,
        ]);

        $otherEnrollment = Enrollment::create([
            'team_id' => $this->team->id,
            'user_id' => $otherUser->id,
            'course_id' => $this->course->id,
            'status' => Enrollment::STATUS_IN_PROGRESS,
            'progress' => 0,
        ]);

        $assessment = $this->createAssessment(maxAttempts: 2);

        $this->assessmentService->startAttempt($assessment, $this->enrollment, $this->user);
        $this->assessmentService->startAttempt($assessment, $this->enrollment, $this->user);
        $this->assertEquals(2, $this->assessmentService->getRemainingAttempts($assessment, $otherEnrollment, $otherUser));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Se ha alcanzado el limite maximo de intentos');

        $this->assessmentService->startAttempt($assessment, $this->enrollment, $this->user);
    }

    public function test_second_user_can_attempt_after_first_blocked(): void
    {
        $otherUser = User::factory()->create([
            'current_team_id' => $this->team->id,
        ]);

        $otherEnrollment = Enrollment::create([
            'team_id' => $this->team->id,
            'user_id' => $otherUser->id,
            'course_id' => $this->course->id,
            'status' => Enrollment::STATUS_IN_PROGRESS,
            'progress' => 0,
        ]);

        $assessment = $this->createAssessment(maxAttempts: 1);

        $this->assessmentService->startAttempt($assessment, $this->enrollment, $this->user);

        $attempt = $this->assessmentService->startAttempt($assessment, $otherEnrollment, $otherUser);

        $this->assertNotNull($attempt->id);
        $this->assertEquals($otherUser->id, $attempt->user_id);
    }

    private function createAssessment(?int $maxAttempts): Assessment
    {
        $assessment = Assessment::create([
            'title' => 'Evaluacion',
            'description' => 'Descripcion',
            'course_id' => $this->course->id,
            'lesson_id' => $this->lesson->id,
            'type' => 'quiz',
            'max_score' => 10,
            'passing_score' => 5,
            'max_attempts' => $maxAttempts,
            'duration' => 30,
            'duration_minutes' => 30,
            'show_feedback' => true,
            'active' => true,
        ]);

        $question = $assessment->questions()->create([
            'team_id' => $this->team->id,
            'question_text' => 'Pregunta unica',
            'type' => 'multiple_choice_single',
            'data' => ['required' => true],
        ]);

        $question->question_options()->createMany([
            ['option_text' => 'Correcta', 'is_correct' => true],
            ['option_text' => 'Incorrecta', 'is_correct' => false],
        ]);

        return $assessment;
    }
}
