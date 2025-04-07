<?php

namespace App\Models;

use App\Models\Scopes\TeamScope;
use App\Traits\FilterByTeam;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use FilterByTeam;

    protected $fillable = [
        'name',
        'guard_name',
        'team_id'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new TeamScope);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
