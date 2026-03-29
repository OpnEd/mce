<?php

namespace App\Events\Quality\Training;

use App\Models\Quality\Training\Enrollment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EnrollmentUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Enrollment $enrollment,
        public array $changes = [],
    ) {}
}
