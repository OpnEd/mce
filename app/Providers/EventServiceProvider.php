<?php

namespace App\Providers;

use App\Events\Quality\Training\CourseCreated;
use App\Events\Quality\Training\CourseDeleted;
use App\Events\Quality\Training\CourseUpdated;
use App\Events\Quality\Training\EnrollmentCompleted;
use App\Events\Quality\Training\EnrollmentCreated;
use App\Events\Quality\Training\EnrollmentDeleted;
use App\Events\Quality\Training\EnrollmentUpdated;
use App\Events\Quality\Training\LessonCreated;
use App\Events\Quality\Training\LessonDeleted;
use App\Events\Quality\Training\LessonUpdated;
use App\Events\Quality\Training\ModuleCreated;
use App\Events\Quality\Training\ModuleDeleted;
use App\Events\Quality\Training\ModuleUpdated;
use App\Listeners\Quality\Training\GenerateCertificate;
use App\Listeners\Quality\Training\LogCourseCreated;
use App\Listeners\Quality\Training\LogCourseDeleted;
use App\Listeners\Quality\Training\LogCourseUpdated;
use App\Listeners\Quality\Training\LogEnrollmentCreated;
use App\Listeners\Quality\Training\LogEnrollmentDeleted;
use App\Listeners\Quality\Training\LogEnrollmentUpdated;
use App\Listeners\Quality\Training\LogLessonCreated;
use App\Listeners\Quality\Training\LogLessonDeleted;
use App\Listeners\Quality\Training\LogLessonUpdated;
use App\Listeners\Quality\Training\LogModuleCreated;
use App\Listeners\Quality\Training\LogModuleDeleted;
use App\Listeners\Quality\Training\LogModuleUpdated;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CourseCreated::class => [
            LogCourseCreated::class,
        ],
        CourseUpdated::class => [
            LogCourseUpdated::class,
        ],
        CourseDeleted::class => [
            LogCourseDeleted::class,
        ],
        ModuleCreated::class => [
            LogModuleCreated::class,
        ],
        ModuleUpdated::class => [
            LogModuleUpdated::class,
        ],
        ModuleDeleted::class => [
            LogModuleDeleted::class,
        ],
        LessonCreated::class => [
            LogLessonCreated::class,
        ],
        LessonUpdated::class => [
            LogLessonUpdated::class,
        ],
        LessonDeleted::class => [
            LogLessonDeleted::class,
        ],
        EnrollmentCreated::class => [
            LogEnrollmentCreated::class,
        ],
        EnrollmentUpdated::class => [
            LogEnrollmentUpdated::class,
        ],
        EnrollmentDeleted::class => [
            LogEnrollmentDeleted::class,
        ],
        EnrollmentCompleted::class => [
            GenerateCertificate::class,
        ],
    ];

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
