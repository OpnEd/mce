<?php

namespace Tests\Feature\Quality\Training;

use App\Models\Quality\Training\Assessment;
use App\Models\Quality\Training\AssessmentAttempt;
use App\Models\Quality\Training\Course;
use App\Models\Quality\Training\Enrollment;
use App\Models\Quality\Training\EnrollmentLesson;
use App\Models\Quality\Training\Lesson;
use App\Models\Quality\Training\Module;
use App\Models\Quality\Training\Question;
use App\Models\User;
use App\Services\Quality\AssessmentService;
use App\Services\Quality\EnrollmentLessonService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;
    protected \App\Models\Team $team;
    protected Course $course;
    protected Module $module;
    protected Lesson $lesson;
    protected Assessment $assessment;
    protected Enrollment $enrollment;
    protected AssessmentService $assessmentService;
    protected EnrollmentLessonService $enrollmentLessonService;
    protected Question $singleChoiceQuestion;
    protected Question $multipleChoiceQuestion;
    protected Question $trueFalseQuestion;
    protected ?Question $freeTextQuestion = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->assessmentService = app(AssessmentService::class);
        $this->enrollmentLessonService = app(EnrollmentLessonService::class);
        $this->team = \App\Models\Team::factory()->create();
        $this->student = User::factory()->create([
            'current_team_id' => $this->team->id,
        ]);

        $this->course = Course::factory()->create([
            'team_id' => $this->team->id,
            'title' => 'Curso de Capacitacion',
            'active' => true,
        ]);

        $this->module = Module::create([
            'course_id' => $this->course->id,
            'title' => 'Modulo 1',
            'objective' => 'Objetivo',
            'description' => 'Descripcion',
            'duration' => 60,
            'order' => 1,
            'active' => true,
        ]);

        $this->lesson = Lesson::create([
            'module_id' => $this->module->id,
            'title' => 'Leccion 1',
            'objective' => 'Objetivo',
            'description' => 'Descripcion',
            'duration' => 15,
            'order' => 1,
            'content' => 'Contenido',
            'active' => true,
            'completion_mode' => Lesson::COMPLETION_MODE_ASSESSMENT_REQUIRED,
        ]);

        $this->assessment = Assessment::create([
            'title' => 'Evaluacion Leccion 1',
            'description' => 'Instrucciones',
            'course_id' => $this->course->id,
            'lesson_id' => $this->lesson->id,
            'type' => 'quiz',
            'max_score' => 100,
            'passing_score' => 70,
            'max_attempts' => 3,
            'duration' => 10,
            'duration_minutes' => 10,
            'show_feedback' => true,
            'active' => true,
        ]);

        $this->seedQuestions();

        $this->enrollment = Enrollment::create([
            'team_id' => $this->team->id,
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => Enrollment::STATUS_IN_PROGRESS,
            'progress' => 0,
        ]);
    }

    public function test_student_can_start_assessment_attempt(): void
    {
        $this->actingAs($this->student);

        $attempt = $this->assessmentService->startAttempt(
            assessment: $this->assessment,
            enrollment: $this->enrollment,
            user: $this->student,
        );

        $this->assertInstanceOf(AssessmentAttempt::class, $attempt);
        $this->assertEquals('in_progress', $attempt->status);
        $this->assertNull($attempt->completed_at);
        $this->assertEquals($this->student->id, $attempt->user_id);
    }

    public function test_student_can_submit_assessment_and_receive_score(): void
    {
        $this->actingAs($this->student);

        $attempt = $this->assessmentService->startAttempt(
            assessment: $this->assessment,
            enrollment: $this->enrollment,
            user: $this->student,
        );

        $answers = $this->buildPartiallyCorrectAnswers();

        $submittedAttempt = $this->assessmentService->submitAttempt($attempt, $answers);
        $gradedAttempt = $this->assessmentService->gradeAttempt($submittedAttempt);

        $this->assertEquals('completed', $gradedAttempt->status);
        $this->assertEquals(66.67, $gradedAttempt->score);
        $this->assertFalse($gradedAttempt->passed);
        $this->assertNotNull($gradedAttempt->completed_at);
        $this->assertCount(4, $gradedAttempt->userAnswers);
    }

    public function test_student_passes_assessment_with_all_correct_answers_and_updates_progress(): void
    {
        $this->actingAs($this->student);

        $attempt = $this->assessmentService->startAttempt(
            assessment: $this->assessment,
            enrollment: $this->enrollment,
            user: $this->student,
        );

        $gradedAttempt = $this->assessmentService->gradeAttempt($attempt, $this->buildCorrectAnswers());

        $this->enrollment->refresh();

        $this->assertTrue($gradedAttempt->passed);
        $this->assertEquals(100.0, $gradedAttempt->score);
        $this->assertNotNull($gradedAttempt->passed_at);
        $this->assertEquals(100, $this->enrollment->progress);
        $this->assertEquals(Enrollment::STATUS_COMPLETED, $this->enrollment->status);
        $this->assertEquals(100.0, $this->enrollment->score_final);
    }

    public function test_student_cannot_access_other_students_enrollment(): void
    {
        $otherStudent = User::factory()->create([
            'current_team_id' => $this->team->id,
        ]);

        $this->actingAs($otherStudent);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('El usuario no esta inscrito en esta matricula');

        $this->assessmentService->startAttempt(
            assessment: $this->assessment,
            enrollment: $this->enrollment,
            user: $otherStudent,
        );
    }

    public function test_free_text_answers_are_stored_but_not_counted_for_automatic_grading(): void
    {
        $this->addFreeTextQuestion();
        $this->actingAs($this->student);

        $attempt = $this->assessmentService->startAttempt(
            assessment: $this->assessment->fresh(),
            enrollment: $this->enrollment,
            user: $this->student,
        );

        $answers = $this->buildCorrectAnswers() + [
            $this->freeTextQuestion->id => 'Respuesta abierta del estudiante',
        ];

        $gradedAttempt = $this->assessmentService->gradeAttempt($attempt, $answers);
        $summary = $this->assessmentService->buildAttemptSummary($gradedAttempt);

        $this->assertEquals(100.0, $gradedAttempt->score);
        $this->assertEquals(4, $summary['total_questions']);
        $this->assertEquals(3, $summary['gradable_questions']);
        $this->assertSame('Respuesta abierta del estudiante', $gradedAttempt->responses[$this->freeTextQuestion->id]);
    }

    public function test_grade_attempt_accepts_answers_directly(): void
    {
        $this->actingAs($this->student);

        $attempt = $this->assessmentService->startAttempt(
            assessment: $this->assessment,
            enrollment: $this->enrollment,
            user: $this->student,
        );

        $gradedAttempt = $this->assessmentService->gradeAttempt($attempt, $this->buildCorrectAnswers());

        $this->assertTrue($gradedAttempt->passed);
        $this->assertNotEmpty($gradedAttempt->responses);
        $this->assertCount(4, $gradedAttempt->userAnswers);
    }

    public function test_failed_retry_does_not_downgrade_a_previously_passed_lesson(): void
    {
        $this->actingAs($this->student);

        $passedAttempt = $this->assessmentService->startAttempt(
            assessment: $this->assessment,
            enrollment: $this->enrollment,
            user: $this->student,
        );

        $this->assessmentService->gradeAttempt($passedAttempt, $this->buildCorrectAnswers());

        $failedAttempt = $this->assessmentService->startAttempt(
            assessment: $this->assessment,
            enrollment: $this->enrollment,
            user: $this->student,
        );

        $this->assessmentService->gradeAttempt($failedAttempt, $this->buildPartiallyCorrectAnswers());

        $lessonProgress = EnrollmentLesson::query()
            ->where('enrollment_id', $this->enrollment->id)
            ->where('lesson_id', $this->lesson->id)
            ->firstOrFail();

        $this->enrollment->refresh();

        $this->assertSame(EnrollmentLesson::STATUS_PASSED, $lessonProgress->status);
        $this->assertTrue($lessonProgress->passed);
        $this->assertEquals(100, $this->enrollment->progress);
        $this->assertEquals(Enrollment::STATUS_COMPLETED, $this->enrollment->status);
    }

    public function test_consumption_only_lesson_completes_enrollment_without_assessment_score(): void
    {
        $course = Course::factory()->create([
            'team_id' => $this->team->id,
            'title' => 'Curso consumo',
            'active' => true,
        ]);

        $module = Module::create([
            'course_id' => $course->id,
            'title' => 'Modulo consumo',
            'objective' => 'Objetivo',
            'description' => 'Descripcion',
            'duration' => 30,
            'order' => 1,
            'active' => true,
        ]);

        $lesson = Lesson::create([
            'module_id' => $module->id,
            'title' => 'Leccion consumo',
            'objective' => 'Objetivo',
            'description' => 'Descripcion',
            'duration' => 10,
            'order' => 1,
            'content' => 'Contenido',
            'active' => true,
            'completion_mode' => Lesson::COMPLETION_MODE_CONSUMPTION_ONLY,
        ]);

        $enrollment = Enrollment::create([
            'team_id' => $this->team->id,
            'user_id' => $this->student->id,
            'course_id' => $course->id,
            'status' => Enrollment::STATUS_IN_PROGRESS,
            'progress' => 0,
        ]);

        $enrollmentLesson = $this->enrollmentLessonService->getOrCreate($enrollment, $lesson);
        $this->enrollmentLessonService->markConsumed($enrollmentLesson);

        $enrollment->refresh();

        $this->assertEquals(100, $enrollment->progress);
        $this->assertEquals(Enrollment::STATUS_COMPLETED, $enrollment->status);
        $this->assertNull($enrollment->score_final);
    }

    private function seedQuestions(): void
    {
        $this->singleChoiceQuestion = $this->assessment->questions()->create([
            'team_id' => $this->team->id,
            'question_text' => 'Pregunta unica',
            'type' => Question::TYPE_MULTIPLE_CHOICE_SINGLE,
            'data' => ['required' => true],
        ]);

        $this->singleChoiceQuestion->question_options()->createMany([
            ['option_text' => 'Correcta', 'is_correct' => true],
            ['option_text' => 'Incorrecta', 'is_correct' => false],
        ]);

        $this->multipleChoiceQuestion = $this->assessment->questions()->create([
            'team_id' => $this->team->id,
            'question_text' => 'Pregunta multiple',
            'type' => Question::TYPE_MULTIPLE_CHOICE_MULTIPLE,
            'data' => ['required' => true],
        ]);

        $this->multipleChoiceQuestion->question_options()->createMany([
            ['option_text' => 'Opcion A', 'is_correct' => true],
            ['option_text' => 'Opcion B', 'is_correct' => true],
            ['option_text' => 'Opcion C', 'is_correct' => false],
        ]);

        $this->trueFalseQuestion = $this->assessment->questions()->create([
            'team_id' => $this->team->id,
            'question_text' => 'Pregunta verdadero o falso',
            'type' => Question::TYPE_TRUE_FALSE,
            'data' => ['required' => true],
        ]);

        $this->trueFalseQuestion->question_options()->createMany([
            ['option_text' => 'Verdadero', 'is_correct' => true],
            ['option_text' => 'Falso', 'is_correct' => false],
        ]);
    }

    private function addFreeTextQuestion(): void
    {
        $this->freeTextQuestion = $this->assessment->questions()->create([
            'team_id' => $this->team->id,
            'question_text' => 'Pregunta abierta',
            'type' => Question::TYPE_FREE_TEXT,
            'data' => ['required' => true],
        ]);
    }

    private function buildCorrectAnswers(): array
    {
        return [
            $this->singleChoiceQuestion->id => $this->singleChoiceQuestion->question_options()->where('is_correct', true)->value('id'),
            $this->multipleChoiceQuestion->id => $this->multipleChoiceQuestion->question_options()->where('is_correct', true)->pluck('id')->all(),
            $this->trueFalseQuestion->id => $this->trueFalseQuestion->question_options()->where('is_correct', true)->value('id'),
        ];
    }

    private function buildPartiallyCorrectAnswers(): array
    {
        return [
            $this->singleChoiceQuestion->id => $this->singleChoiceQuestion->question_options()->where('is_correct', true)->value('id'),
            $this->multipleChoiceQuestion->id => $this->multipleChoiceQuestion->question_options()->where('is_correct', true)->pluck('id')->all(),
            $this->trueFalseQuestion->id => $this->trueFalseQuestion->question_options()->where('is_correct', false)->value('id'),
        ];
    }
}
