<?php

namespace App\Events\Quality\Training;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EnrollmentDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public array $enrollmentData,
        public int $enrollmentId,
    ) {}
}
