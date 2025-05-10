<?php

namespace App\Policies;

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
        $team = Filament::getTenant();
        $teamId = $team->id;

        return $user->hasPermissionTo('view-purchase')
            && ($team && $user->teams()->where('teams.id', $teamId)->exists());
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Purchase $purchase): bool
    {
        $team = Filament::getTenant();
        $teamId = $team->id;

        return $user->hasPermissionTo('view-purchase')
            && ($team && $user->teams()->where('teams.id', $teamId)->exists())
            && $purchase->team_id === $teamId;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $team = Filament::getTenant();
        $teamId = $team->id;

        return $user->hasPermissionTo('create-purchase')
            && ($team && $user->teams()->where('teams.id', $teamId)->exists());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Purchase $purchase): bool
    {
        $team = Filament::getTenant();
        $teamId = $team->id;

        return $user->hasPermissionTo('edit-purchase')
            && $purchase->status === 'pending'
            && $purchase->team_id === $teamId
            && ($team && $user->teams()->where('teams.id', $teamId)->exists());
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Purchase $purchase): bool
    {
        $team = Filament::getTenant();
        $teamId = $team->id;

        return $user->hasPermissionTo('delete-purchase')
            && $purchase->status === 'pending'
            && $purchase->team_id === $teamId
            && ($team && $user->teams()->where('teams.id', $teamId)->exists());
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Purchase $purchase): bool
    {
        $team = Filament::getTenant();
        $teamId = $team->id;

        return $user->hasPermissionTo('restore-purchase')
            && $purchase->status === 'pending'
            && $purchase->team_id === $teamId
            && ($team && $user->teams()->where('teams.id', $teamId)->exists());
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Purchase $purchase): bool
    {
        $team = Filament::getTenant();
        $teamId = $team->id;

        return $user->hasPermissionTo('force-delete-purchase')
            && $purchase->status === 'pending'
            && $purchase->team_id === $teamId
            && ($team && $user->teams()->where('teams.id', $teamId)->exists());
    }

    public function confirm(User $user, Purchase $purchase): bool
    {
        $team = Filament::getTenant();
        $teamId = $team->id;

        return $user->hasPermissionTo('confirm-purchase')
            && $purchase->status === 'pending'
            && $purchase->team_id === $teamId
            && ($team && $user->teams()->where('teams.id', $teamId)->exists());
    }
}
