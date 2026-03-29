<?php

namespace Tests\Feature\Quality\Training;

use App\Models\Quality\Training\Course;
use App\Models\Quality\Training\Module;
use App\Models\Quality\Training\Lesson;
use App\Models\Quality\Training\Enrollment;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminResourcesTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $instructor;
    protected User $student;
    protected Team $team;
    protected Course $course;
    protected Module $module;
    protected Lesson $lesson;

    protected function setUp(): void
    {
        parent::setUp();

        // Create team
        $this->team = Team::factory()->create();

        // Create users with roles
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->instructor = User::factory()->create();
        $this->instructor->assignRole('instructor');

        $this->student = User::factory()->create();

        // Create course
        $this->course = Course::factory()
            ->for($this->team)
            ->create([
                'instructor_id' => $this->instructor->id,
                'title' => 'Test Course',
                'active' => true,
            ]);

        // Create module
        $this->module = Module::factory()
            ->for($this->course)
            ->create([
                'title' => 'Test Module',
            ]);

        // Create lesson
        $this->lesson = Lesson::factory()
            ->for($this->module)
            ->create([
                'title' => 'Test Lesson',
            ]);
    }

    /**
     * Test CourseResource Authorization
     */
    public function test_instructor_can_view_own_courses()
    {
        $this->assertTrue($this->instructor->can('view', $this->course));
    }

    public function test_instructor_can_update_own_course()
    {
        $this->assertTrue($this->instructor->can('update', $this->course));
    }

    public function test_instructor_cannot_update_others_course()
    {
        $otherInstructor = User::factory()->create();
        $otherInstructor->assignRole('instructor');
        
        $otherCourse = Course::factory()
            ->for($this->team)
            ->create([
                'instructor_id' => $otherInstructor->id,
            ]);

        $this->assertFalse($this->instructor->can('update', $otherCourse));
    }

    public function test_instructor_cannot_delete_course()
    {
        $this->assertFalse($this->instructor->can('delete', $this->course));
    }

    public function test_admin_can_delete_any_course()
    {
        $this->assertTrue($this->admin->can('delete', $this->course));
    }

    public function test_instructor_can_create_course()
    {
        $this->assertTrue($this->instructor->can('create', Course::class));
    }

    public function test_student_cannot_create_course()
    {
        $this->assertFalse($this->student->can('create', Course::class));
    }

    /**
     * Test ModuleResource Authorization
     */
    public function test_instructor_can_update_module_in_own_course()
    {
        $this->assertTrue($this->instructor->can('update', $this->module));
    }

    public function test_instructor_cannot_update_module_in_others_course()
    {
        $otherInstructor = User::factory()->create();
        $otherInstructor->assignRole('instructor');
        
        $otherCourse = Course::factory()
            ->for($this->team)
            ->create([
                'instructor_id' => $otherInstructor->id,
            ]);

        $otherModule = Module::factory()
            ->for($otherCourse)
            ->create();

        $this->assertFalse($this->instructor->can('update', $otherModule));
    }

    public function test_instructor_cannot_delete_module()
    {
        $this->assertFalse($this->instructor->can('delete', $this->module));
    }

    public function test_admin_can_delete_module()
    {
        $this->assertTrue($this->admin->can('delete', $this->module));
    }

    /**
     * Test LessonResource Authorization
     */
    public function test_instructor_can_update_lesson_in_own_course()
    {
        $this->assertTrue($this->instructor->can('update', $this->lesson));
    }

    public function test_instructor_cannot_update_lesson_in_others_course()
    {
        $otherInstructor = User::factory()->create();
        $otherInstructor->assignRole('instructor');
        
        $otherCourse = Course::factory()
            ->for($this->team)
            ->create([
                'instructor_id' => $otherInstructor->id,
            ]);

        $otherModule = Module::factory()
            ->for($otherCourse)
            ->create();

        $otherLesson = Lesson::factory()
            ->for($otherModule)
            ->create();

        $this->assertFalse($this->instructor->can('update', $otherLesson));
    }

    public function test_instructor_cannot_delete_lesson()
    {
        $this->assertFalse($this->instructor->can('delete', $this->lesson));
    }

    /**
     * Test EnrollmentResource Authorization
     */
    public function test_student_can_view_own_enrollment()
    {
        $enrollment = Enrollment::factory()
            ->for($this->team)
            ->create([
                'user_id' => $this->student->id,
                'course_id' => $this->course->id,
            ]);

        $this->assertTrue($this->student->can('view', $enrollment));
    }

    public function test_student_cannot_view_others_enrollment()
    {
        $other = User::factory()->create();
        
        $enrollment = Enrollment::factory()
            ->for($this->team)
            ->create([
                'user_id' => $other->id,
                'course_id' => $this->course->id,
            ]);

        $this->assertFalse($this->student->can('view', $enrollment));
    }

    public function test_instructor_can_view_enrollment_in_own_course()
    {
        $enrollment = Enrollment::factory()
            ->for($this->team)
            ->create([
                'user_id' => $this->student->id,
                'course_id' => $this->course->id,
            ]);

        $this->assertTrue($this->instructor->can('view', $enrollment));
    }

    public function test_instructor_can_update_enrollment_in_own_course()
    {
        $enrollment = Enrollment::factory()
            ->for($this->team)
            ->create([
                'user_id' => $this->student->id,
                'course_id' => $this->course->id,
            ]);

        $this->assertTrue($this->instructor->can('update', $enrollment));
    }

    /**
     * Test CourseResource CRUD
     */
    public function test_can_create_course_with_valid_data()
    {
        $data = [
            'title' => 'New Test Course',
            'objective' => 'Test Objective',
            'description' => 'Test Description',
            'instructor_id' => $this->instructor->id,
            'duration' => 120,
            'type' => 'synchronous',
            'level' => 'beginner',
            'category' => 'test_category',
            'price' => 99.99,
            'team_id' => $this->team->id,
            'active' => true,
        ];

        $course = Course::create($data);

        $this->assertDatabaseHas('courses', [
            'title' => 'New Test Course',
            'instructor_id' => $this->instructor->id,
        ]);
    }

    public function test_can_update_course()
    {
        $this->course->update([
            'title' => 'Updated Course Title',
            'active' => false,
        ]);

        $this->assertDatabaseHas('courses', [
            'id' => $this->course->id,
            'title' => 'Updated Course Title',
            'active' => false,
        ]);
    }

    /**
     * Test ModuleResource CRUD
     */
    public function test_can_create_module_with_valid_data()
    {
        $data = [
            'course_id' => $this->course->id,
            'title' => 'New Test Module',
            'objective' => 'Test Objective',
            'description' => 'Test Description',
            'order' => 2,
            'duration' => 60,
            'team_id' => $this->team->id,
            'active' => true,
        ];

        $module = Module::create($data);

        $this->assertDatabaseHas('modules', [
            'course_id' => $this->course->id,
            'title' => 'New Test Module',
        ]);
    }

    public function test_can_update_module()
    {
        $this->module->update([
            'title' => 'Updated Module Title',
            'order' => 3,
        ]);

        $this->assertDatabaseHas('modules', [
            'id' => $this->module->id,
            'title' => 'Updated Module Title',
            'order' => 3,
        ]);
    }

    /**
     * Test LessonResource CRUD
     */
    public function test_can_create_lesson_with_valid_data()
    {
        $data = [
            'module_id' => $this->module->id,
            'title' => 'New Test Lesson',
            'objective' => 'Test Objective',
            'description' => 'Test Description',
            'duration' => 30,
            'order' => 2,
            'completion_mode' => Lesson::COMPLETION_MODE_CONSUMPTION_ONLY,
            'team_id' => $this->team->id,
            'active' => true,
        ];

        $lesson = Lesson::create($data);

        $this->assertDatabaseHas('lessons', [
            'module_id' => $this->module->id,
            'title' => 'New Test Lesson',
        ]);
    }

    public function test_can_update_lesson()
    {
        $this->lesson->update([
            'title' => 'Updated Lesson Title',
            'duration' => 45,
            'completion_mode' => Lesson::COMPLETION_MODE_ASSESSMENT_REQUIRED,
        ]);

        $this->assertDatabaseHas('lessons', [
            'id' => $this->lesson->id,
            'title' => 'Updated Lesson Title',
            'duration' => 45,
        ]);
    }

    /**
     * Test EnrollmentResource CRUD
     */
    public function test_can_create_enrollment_with_valid_data()
    {
        $data = [
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'team_id' => $this->team->id,
            'status' => Enrollment::STATUS_IN_PROGRESS,
            'progress' => 0,
        ];

        $enrollment = Enrollment::create($data);

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);
    }

    public function test_can_update_enrollment_status()
    {
        $enrollment = Enrollment::factory()
            ->for($this->team)
            ->create([
                'user_id' => $this->student->id,
                'course_id' => $this->course->id,
                'status' => Enrollment::STATUS_IN_PROGRESS,
                'progress' => 50,
            ]);

        $enrollment->update([
            'status' => Enrollment::STATUS_COMPLETED,
            'progress' => 100,
            'completed_at' => now(),
        ]);

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'status' => Enrollment::STATUS_COMPLETED,
            'progress' => 100,
        ]);
    }

    /**
     * Test Relationships
     */
    public function test_course_has_many_modules()
    {
        $this->assertCount(1, $this->course->modules);
    }

    public function test_module_belongs_to_course()
    {
        $this->assertTrue($this->module->course->is($this->course));
    }

    public function test_module_has_many_lessons()
    {
        $this->assertCount(1, $this->module->lessons);
    }

    public function test_lesson_belongs_to_module()
    {
        $this->assertTrue($this->lesson->module->is($this->module));
    }

    public function test_course_has_many_enrollments()
    {
        Enrollment::factory()
            ->for($this->team)
            ->create([
                'course_id' => $this->course->id,
            ]);

        $this->assertGreaterThanOrEqual(1, $this->course->enrollments->count());
    }
}
