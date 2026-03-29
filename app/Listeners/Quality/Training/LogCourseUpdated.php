<?php

namespace App\Listeners\Quality\Training;

use App\Events\Quality\Training\CourseUpdated;
use App\Services\Quality\AuditService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogCourseUpdated implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    public function handle(CourseUpdated $event): void
    {
        if (empty($event->changes)) {
            return;
        }

        $oldValues = [];
        $newValues = [];

        foreach ($event->changes as $field => $newValue) {
            $newValues[$field] = $newValue;
            $oldValues[$field] = $event->course->getOriginal($field);
        }

        AuditService::logUpdate(
            $event->course->team,
            'Course',
            $event->course->id,
            $oldValues,
            $newValues,
            description: "Curso '{$event->course->title}' actualizado",
        );
    }
}
