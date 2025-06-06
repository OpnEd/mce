<?php

namespace App\Policies;

use App\Helpers\CanCreateHelper;
use App\Helpers\CanDeleteHelper;
use App\Helpers\CanForceDeleteHelper;
use App\Helpers\CanRestoreHelper;
use App\Helpers\CanUpdateHelper;
use App\Helpers\CanViewAnyHelper;
use App\Helpers\CanViewHelper;
use App\Models\Dispatch;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DispatchPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return CanViewAnyHelper::canViewAny($user, 'view-dispatch');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Dispatch $dispatch): bool
    {
        return CanViewHelper::canView($user, $dispatch, 'view-dispatch');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return CanCreateHelper::canCreate($user, 'create-dispatch');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Dispatch $dispatch): bool
    {
        return CanUpdateHelper::canUpdate($user, $dispatch, 'edit-dispatch');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Dispatch $dispatch): bool
    {
        return CanDeleteHelper::canDelete($user, $dispatch, 'delete-dispatch');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Dispatch $dispatch): bool
    {
        return CanRestoreHelper::canRestore($user, $dispatch, 'restore-dispatch');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Dispatch $dispatch): bool
    {
        return CanForceDeleteHelper::canForceDelete($user, $dispatch, 'force-delete-dispatch');
    }
}
