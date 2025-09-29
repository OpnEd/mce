<?php

namespace App\Policies;

use App\Helpers\CanUpdateHelper;
use App\Helpers\CanViewAnyHelper;
use App\Helpers\CanViewHelper;
use App\Models\Quality\ManagementIndicatorTeam;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ManagementIndicatorTeamPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return CanViewAnyHelper::canViewAny($user, 'view-management-indicator-teams');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ManagementIndicatorTeam $model): bool
    {
        return CanViewHelper::canView($user, $model, 'view-management-indicator-teams');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ManagementIndicatorTeam $model): bool
    {
        return CanUpdateHelper::canUpdate($user, $model, 'update-management-indicator-teams');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ManagementIndicatorTeam $managementIndicatorTeam): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ManagementIndicatorTeam $managementIndicatorTeam): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ManagementIndicatorTeam $managementIndicatorTeam): bool
    {
        return false;
    }
}
