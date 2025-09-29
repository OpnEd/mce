<?php

namespace App\Models\Quality;

use App\Models\ManagementIndicator;
use App\Models\Role;
use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ManagementIndicatorTeam extends Pivot
{
    protected $table = 'management_indicator_team';

    protected $fillable = [
        'team_id',
        'management_indicator_id',
        'role_id',
        'periodicity',
        'indicator_goal',
    ];

    protected $casts = [
        'indicator_goal' => 'decimal:2',
    ];

    public function managementIndicator(): BelongsTo
    {
        return $this->belongsTo(ManagementIndicator::class);
    }


    /**
     * Relación al Role desde el pivote.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }


}
