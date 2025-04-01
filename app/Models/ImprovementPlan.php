<?php

namespace App\Models;

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
        'title',
        'objective',
        'description',
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
        'status' => 'string',
        'data' => 'array',
    ];

    /* public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    } */

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function checklist_items()
    {
        return $this->belongsToMany(ChecklistItem::class, 'checklist_item_improvement_plan');
    }
}
