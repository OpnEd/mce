<?php

namespace App\Models\Quality\Records\Improvement;

use App\Enums\ImprovementPlanStatus;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImprovementPlan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'team_id',
        'checklist_item_answer_id',
        'title',
        'objective',
        'descripcion',
        //'role_id',
        'ends_at',
        'status',
        'data',
    ];

    protected $casts = [
        'tenant_id' => 'integer',
        'name' => 'string',
        'role_id' => 'integer',
        'ends_at' => 'datetime',
        'status' => ImprovementPlanStatus::class,
        'data' => 'array',
    ];

    public static function markOverdue(): int
    {
        return static::query()
            ->whereNotNull('ends_at')
            ->where('ends_at', '<', now())
            ->whereIn('status', [
                ImprovementPlanStatus::Pending->value,
                ImprovementPlanStatus::InProgressOnTime->value,
            ])
            ->update(['status' => ImprovementPlanStatus::InProgressLate->value]);
    }

    /* public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    } */

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function checklistItemAnswer(): BelongsTo
    {
        return $this->belongsTo(ChecklistItemAnswer::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
