<?php

namespace App\Events\Quality\Training;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LessonDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public array $lessonData,
        public int $lessonId,
    ) {}
}
