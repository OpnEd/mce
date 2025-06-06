<?php

namespace App\Policies;

use App\Helpers\CanCreateHelper;
use App\Helpers\CanDeleteHelper;
use App\Helpers\CanForceDeleteHelper;
use App\Helpers\CanRestoreHelper;
use App\Helpers\CanUpdateHelper;
use App\Helpers\CanViewAnyHelper;
use App\Helpers\CanViewHelper;
use App\Models\Purchase;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;

class PurchasePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return CanViewAnyHelper::canViewAny($user, 'view-purchase');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Purchase $model): bool
    {
        return CanViewHelper::canView($user, $model, 'view-purchase');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return CanCreateHelper::canCreate($user, 'create-purchase');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Purchase $model): bool
    {
        return CanUpdateHelper::canUpdate($user, $model, 'edit-purchase')
            && $model->status === 'pending';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Purchase $model): bool
    {
        return CanDeleteHelper::canDelete($user, $model, 'delete-purchase');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Purchase $model): bool
    {
        return CanRestoreHelper::canRestore($user, $model, 'restore-purchase');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Purchase $model): bool
    {
        return CanForceDeleteHelper::canForceDelete($user, $model, 'force-delete-purchase');
    }

    public function confirm(User $user, Purchase $model): bool
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

        return $role->permissions->contains('name', 'confirm-purchase')
            && $model->status === 'pending'
            && $model->team_id === $teamId
            && $user->teams()->where('teams.id', $teamId)->exists();
    }
}
