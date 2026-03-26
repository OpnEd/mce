<?php

namespace App\Models\Quality\Records\Improvement;

use App\Enums\TaskStatus;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Quality\Records\Improvement\ChecklistItem;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'team_id',
        'user_id',
        'checklist_item_id',
        'improvement_plan_id',
        'causal_analysis',
        'description',
        'ends_at',
        'status',
    ];

    protected $casts = [
        'causal_analysis' => 'array',
        'ends_at' => 'datetime',
        'status' => TaskStatus::class,
    ];
    public function checklistItemAnswer(): BelongsTo
    {
        return $this->belongsTo(ChecklistItemAnswer::class);
    }

    public function checklistItem(): BelongsTo
    {
        return $this->belongsTo(ChecklistItem::class);
    }

    public function improvementPlan(): BelongsTo
    {
        return $this->belongsTo(ImprovementPlan::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
