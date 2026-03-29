<?php

namespace App\Listeners\Quality\Training;

use App\Events\Quality\Training\CourseCreated;
use App\Services\Quality\AuditService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Request;

class LogCourseCreated implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    public function handle(CourseCreated $event): void
    {
        AuditService::logCreate(
            $event->course->team,
            'Course',
            $event->course->id,
            description: "Curso '{$event->course->title}' creado",
        );
    }
}
