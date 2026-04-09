<?php

namespace Tests\Feature\Quality\Training;

use App\Models\Quality\Training\Course;
use App\Models\Quality\Training\Lesson;
use App\Models\Quality\Training\Module;
use App\Models\Team;
use App\Models\User;
use App\Services\Quality\TrainingService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainingTenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected Team $team;

    protected Team $otherTeam;

    protected User $student;

    protected TrainingService $trainingService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->trainingService = app(TrainingService::class);
        $this->team = Team::factory()->create();
        $this->otherTeam = Team::factory()->create();
        $this->student = User::factory()->create([
            'current_team_id' => $this->team->id,
        ]);
    }

    public function test_team_only_sees_owned_and_shared_courses_in_catalog(): void
    {
        $owned = $this->createCourseGraph([
            'team_id' => $this->team->id,
            'title' => 'Curso propio',
        ]);

        $sharedGlobal = $this->createCourseGraph([
            'team_id' => null,
            'title' => 'Curso global compartido',
        ]);
        $sharedGlobal->teams()->attach($this->team->id);

        $hiddenGlobal = $this->createCourseGraph([
            'team_id' => null,
            'title' => 'Curso global no compartido',
        ]);

        $foreign = $this->createCourseGraph([
            'team_id' => $this->otherTeam->id,
            'title' => 'Curso de otro equipo',
        ]);

        $courses = $this->trainingService->listAvailableCourses($this->team->id);

        $this->assertSame(
            ['Curso global compartido', 'Curso propio'],
            $courses->pluck('title')->values()->all()
        );
        $this->assertTrue($courses->contains('id', $owned->id));
        $this->assertTrue($courses->contains('id', $sharedGlobal->id));
        $this->assertFalse($courses->contains('id', $hiddenGlobal->id));
        $this->assertFalse($courses->contains('id', $foreign->id));
    }

    public function test_team_cannot_enroll_into_course_from_another_team(): void
    {
        $foreign = $this->createCourseGraph([
            'team_id' => $this->otherTeam->id,
            'title' => 'Curso ajeno',
        ]);

        $this->expectException(ModelNotFoundException::class);

        $this->trainingService->enroll($this->team->id, $this->student->id, $foreign->id);
    }

    private function createCourseGraph(array $courseAttributes): Course
    {
        $course = Course::factory()->create($courseAttributes + [
            'active' => true,
        ]);

        $module = Module::query()->create([
            'course_id' => $course->id,
            'title' => 'Modulo base',
            'duration' => 20,
            'order' => 1,
            'active' => true,
        ]);

        Lesson::query()->create([
            'module_id' => $module->id,
            'title' => 'Leccion base',
            'duration' => 10,
            'order' => 1,
            'content' => ['body' => 'contenido'],
            'completion_mode' => Lesson::COMPLETION_MODE_CONSUMPTION_ONLY,
            'active' => true,
        ]);

        return $course;
    }
}
