<?php

namespace App\Listeners\Quality\Training;

use App\Events\Quality\Training\LessonUpdated;
use App\Services\Quality\AuditService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogLessonUpdated implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(LessonUpdated $event): void
    {
        if (empty($event->changes)) {
            return;
        }

        $oldValues = [];
        $newValues = [];

        foreach ($event->changes as $field => $newValue) {
            $newValues[$field] = $newValue;
            $oldValues[$field] = $event->lesson->getOriginal($field);
        }

        AuditService::logUpdate(
            $event->lesson->module->course->team,
            'Lesson',
            $event->lesson->id,
            $oldValues,
            $newValues,
            description: "Lección '{$event->lesson->title}' actualizada",
        );
    }
}
