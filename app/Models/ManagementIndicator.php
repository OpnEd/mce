<?php

namespace App\Models;

use App\Models\Quality\ManagementIndicatorTeam;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class ManagementIndicator extends Model
{

    protected $fillable = [
        'quality_goal_id',
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

    public function qualityGoal(): BelongsTo
    {
        return $this->belongsTo(QualityGoal::class);
    }

    /**
     * Equipos que tienen asignado este indicador, con datos extra.
     */
    public function teams(): BelongsToMany
    {
        return $this
            ->belongsToMany(Team::class, 'management_indicator_team')
            ->using(ManagementIndicatorTeam::class)
            ->withPivot([
                'role_id',
                'periodicity',
                'indicator_goal',
            ])
            ->withTimestamps();
    }
}
