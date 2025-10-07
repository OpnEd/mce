<?php

namespace App\Traits;

use App\Models\Scopes\TeamScope;

trait HasTeamScope
{
    public static function bootHasTeamScope()
    {
        static::addGlobalScope(new TeamScope());
    }
}
