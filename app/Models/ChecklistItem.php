<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'team_id',
        'checklist_id',
        'description',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(Checklist::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ChecklistItemAnswer::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function improvement_plans(): BelongsToMany
    {
        return $this->belongsToMany(ImprovementPlan::class, 'checklist_item_improvement_plan');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
