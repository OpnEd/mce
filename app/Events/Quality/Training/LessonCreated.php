<?php

namespace App\Events\Quality\Training;

use App\Models\Quality\Training\Lesson;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LessonCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Lesson $lesson,
    ) {}
}
