<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    ];

    public function checklist_item(): BelongsTo
    {
        return $this->belongsTo(ChecklistItem::class);
    }

    public function improvement_plan(): BelongsTo
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
