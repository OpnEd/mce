<?php

namespace App\Models;

use App\Models\Scopes\TeamScope;
use App\Traits\FilterByTeam;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    //use FilterByTeam;

    protected $fillable = [
        'name',
        'guard_name',
        'team_id',
    ];

    /* protected static function booted()
    {
        static::addGlobalScope(new TeamScope);
    } */

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
