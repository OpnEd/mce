<?php

namespace App\Helpers;

use App\Models\Team;

class TenantHelper {
    public static function currentTeam(): ?Team
    {
        return Team::find(session('team_id'));
    }

    public static function currentTeamId(): int
    {
        return session('team_id', 0);
    }
}
