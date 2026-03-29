<?php

namespace App\Listeners\Quality\Training;

use App\Events\Quality\Training\CourseDeleted;
use App\Services\Quality\AuditService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogCourseDeleted implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    public function handle(CourseDeleted $event): void
    {
        AuditService::logDelete(
            team: new \App\Models\Team(['id' => $event->courseData['team_id'] ?? null]),
            resourceType: 'Course',
            resourceId: $event->courseId,
            description: "Curso '{$event->courseData['title']}' eliminado",
        );
    }
}
