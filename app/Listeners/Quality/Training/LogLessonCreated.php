<?php

namespace App\Listeners\Quality\Training;

use App\Events\Quality\Training\LessonCreated;
use App\Services\Quality\AuditService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogLessonCreated implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(LessonCreated $event): void
    {
        AuditService::logCreate(
            $event->lesson->module->course->team,
            'Lesson',
            $event->lesson->id,
            description: "Lección '{$event->lesson->title}' creada en módulo '{$event->lesson->module->title}'",
        );
    }
}
