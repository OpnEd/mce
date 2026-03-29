<?php

namespace App\Listeners\Quality\Training;

use App\Events\Quality\Training\ModuleUpdated;
use App\Services\Quality\AuditService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogModuleUpdated implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ModuleUpdated $event): void
    {
        if (empty($event->changes)) {
            return;
        }

        $oldValues = [];
        $newValues = [];

        foreach ($event->changes as $field => $newValue) {
            $newValues[$field] = $newValue;
            $oldValues[$field] = $event->module->getOriginal($field);
        }

        AuditService::logUpdate(
            $event->module->course->team,
            'Module',
            $event->module->id,
            $oldValues,
            $newValues,
            description: "Módulo '{$event->module->title}' actualizado",
        );
    }
}
