<?php

namespace App\Helpers;

use App\Models\Team;
use Filament\Facades\Filament;

class CanDeleteHelper
{

    public static function canDelete($user, $model, $permission): bool
    {
        $team = Filament::getTenant();

        if (!$team) {
            return false;
        }

        $teamId = $team->id;

        // Obtiene el primer rol del equipo actual (usando team_id explícito)
        $role = $user->roles()
            ->where('model_has_roles.team_id', $teamId)
            ->where(function ($query) use ($teamId) {
                $query->whereNull('roles.team_id')
                    ->orWhere('roles.team_id', $teamId);
            })
            ->first();

        if (!$role) {
            return false; // Usuario no tiene roles en este equipo
        }

        return $role->permissions->contains('name', $permission)
            && $model->status === 'pending'
            && $model->team_id === $teamId
            && $user->teams()->where('teams.id', $teamId)->exists();
    }
}
