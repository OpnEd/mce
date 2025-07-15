<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class ManagementIndicator extends Model
{

    protected $fillable = [
        'quality_goal_id',
        'role_id',
        'name',
        'objective',
        'type',
        'periodicity',
        'description',
        'information_source',
        'numerator',
        'denominator',
        'denominator_description',
        'indicator_goal'
    ];

    public function quality_goal(): BelongsTo
    {
        return $this->belongsTo(QualityGoal::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
