<?php

namespace App\Listeners\Quality\Training;

use App\Events\Quality\Training\EnrollmentUpdated;
use App\Services\Quality\AuditService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogEnrollmentUpdated implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(EnrollmentUpdated $event): void
    {
        if (empty($event->changes)) {
            return;
        }

        $oldValues = [];
        $newValues = [];

        foreach ($event->changes as $field => $newValue) {
            $newValues[$field] = $newValue;
            $oldValues[$field] = $event->enrollment->getOriginal($field);
        }

        $description = "Matrícula actualizada: {$event->enrollment->user->name}";
        
        if (isset($newValues['status']) && $oldValues['status'] !== $newValues['status']) {
            $description .= " - Estado: {$oldValues['status']} → {$newValues['status']}";
        }
        
        if (isset($newValues['progress'])) {
            $description .= " - Progreso: {$oldValues['progress']}% → {$newValues['progress']}%";
        }

        AuditService::logUpdate(
            $event->enrollment->team,
            'Enrollment',
            $event->enrollment->id,
            $oldValues,
            $newValues,
            description: $description,
        );
    }
}
