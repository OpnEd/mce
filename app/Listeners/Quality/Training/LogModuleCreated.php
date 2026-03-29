<?php

namespace App\Listeners\Quality\Training;

use App\Events\Quality\Training\ModuleCreated;
use App\Services\Quality\AuditService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogModuleCreated implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ModuleCreated $event): void
    {
        AuditService::logCreate(
            $event->module->course->team,
            'Module',
            $event->module->id,
            description: "Módulo '{$event->module->title}' creado en curso '{$event->module->course->title}'",
        );
    }
}
