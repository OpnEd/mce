<?php

namespace App\Services\Quality\Records\Improvement;

use App\Models\Quality\Records\Improvement\ChecklistItemAnswer;
use App\Models\Quality\Records\Improvement\ImprovementPlan;
use App\Enums\ImprovementPlanStatus;
class ImprovementPlanService
{
    public function createFromAnswer(ChecklistItemAnswer $answer): ImprovementPlan
    {
        $answer->loadMissing(['checklistItem.checklist']);

        if ($answer->improvementPlan) {
            return $answer->improvementPlan;
        }

        $checklist = $answer->checklistItem?->checklist;
        $title = $checklist
            ? 'Plan de mejora: ' . $checklist->title
            : 'Plan de mejora';

        $objective = $checklist?->objective
            ?? 'Corregir hallazgos detectados en auditoria.';

        $descripcion = $answer->observations
            ?: ($answer->checklistItem?->description ?? 'Incumplimiento detectado.');

        $defaultStatus = config(
            'quality_improvement.default_status',
            ImprovementPlanStatus::Pending->value
        );
        $defaultStatus = ImprovementPlanStatus::tryFrom($defaultStatus)?->value
            ?? ImprovementPlanStatus::Pending->value;
        $daysToClose = (int) config('quality_improvement.default_days_to_close', 30);

        return ImprovementPlan::create([
            'team_id' => $answer->team_id,
            'checklist_item_answer_id' => $answer->id,
            'title' => $title,
            'objective' => $objective,
            'descripcion' => $descripcion,
            'ends_at' => now()->addDays($daysToClose),
            'status' => $defaultStatus,
            'data' => [
                'source' => 'checklist_item_answer',
                'checklist_id' => $checklist?->id,
                'checklist_item_id' => $answer->checklist_item_id,
                'generated_at' => now()->toDateTimeString(),
            ],
        ]);
    }
}
