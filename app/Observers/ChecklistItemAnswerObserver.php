<?php

namespace App\Observers;

use App\Models\Quality\Records\Improvement\ChecklistItemAnswer;
use App\Services\Quality\Records\Improvement\ImprovementPlanService;

class ChecklistItemAnswerObserver
{
    public function saved(ChecklistItemAnswer $answer): void
    {
        if (! $answer->apply || $answer->meets) {
            return;
        }

        app(ImprovementPlanService::class)->createFromAnswer($answer);
    }
}
