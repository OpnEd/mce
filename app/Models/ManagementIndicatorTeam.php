<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ManagementIndicatorTeam extends Pivot
{
    protected $table = 'management_indicator_team';

    /**
     * Relación al Role desde el pivote.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
