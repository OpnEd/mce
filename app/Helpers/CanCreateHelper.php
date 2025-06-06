<?php

namespace App\Helpers;

use App\Models\Team;
use Filament\Facades\Filament;

class CanCreateHelper
{

    public static function canCreate($user, $permission): bool
    {
        $team = Filament::getTenant();

        return $team
            && $user->hasTeamPermission($permission)
            && $user->teams()->where('teams.id', $team->id)->exists();
    }
}
