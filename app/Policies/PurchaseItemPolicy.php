<?php

namespace App\Policies;

use App\Helpers\CanCreateHelper;
use App\Helpers\CanUpdateHelper;
use App\Helpers\CanViewAnyHelper;
use App\Helpers\CanViewHelper;
use App\Models\PurchaseItem;
use App\Models\User;
use Filament\Facades\Filament;

class PurchaseItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return CanViewAnyHelper::canViewAny($user, 'view-purchase-item');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PurchaseItem $model): bool
    {
        return CanViewHelper::canView($user, $model, 'view-purchase-item');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return CanCreateHelper::canCreate($user, 'create-purchase-item');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PurchaseItem $model): bool
    {
        return CanUpdateHelper::canUpdate($user, $model, 'edit-purchase-item');
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

        return $role->permissions->contains('name', 'edit-purchase-item')
            && $user->teams()->where('teams.id', $teamId)->exists()
            && $model->purchase->team_id === $teamId;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PurchaseItem $model): bool
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

        return $role->permissions->contains('name', 'delete-purchase')
            && $user->teams()->where('teams.id', $teamId)->exists()
            && $model->purchase->status === 'pending'
            && $model->purchase->team_id === $teamId;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PurchaseItem $model): bool
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

        return $role->permissions->contains('name', 'restore-purchase')
            && $user->teams()->where('teams.id', $teamId)->exists()
            && $model->purchase->status === 'pending'
            && $model->purchase->team_id === $teamId;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PurchaseItem $model): bool
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

        return $role->permissions->contains('name', 'force-delete-purchase')
            && $user->teams()->where('teams.id', $teamId)->exists()
            && $model->purchase->status === 'pending'
            && $model->purchase->team_id === $teamId;
    }
}
