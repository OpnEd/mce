<?php

namespace App\Listeners\Quality\Training;

use App\Events\Quality\Training\EnrollmentCreated;
use App\Services\Quality\AuditService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogEnrollmentCreated implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(EnrollmentCreated $event): void
    {
        AuditService::logCreate(
            $event->enrollment->team,
            'Enrollment',
            $event->enrollment->id,
            description: "Matrícula creada: {$event->enrollment->user->name} inscrito en '{$event->enrollment->course->title}'",
        );
    }
}
