<?php

namespace App\Events\Quality\Training;

use App\Models\Quality\Training\Enrollment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EnrollmentCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Enrollment $enrollment,
    ) {}
}
