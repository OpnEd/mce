<?php

namespace App\Policies;

use App\Helpers\CanCancelHelper;
use App\Helpers\CanConfirmHelper;
use App\Helpers\CanCreateHelper;
use App\Helpers\CanDeleteHelper;
use App\Helpers\CanForceDeleteHelper;
use App\Helpers\CanRestoreHelper;
use App\Helpers\CanUpdateHelper;
use App\Helpers\CanViewAnyHelper;
use App\Helpers\CanViewHelper;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SalePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
        //return CanViewAnyHelper::canViewAny($user, 'view-sale');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Sale $model): bool
    {
        return false;
        //return CanViewHelper::canView($user, $model, 'view-sale');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
        //return CanCreateHelper::canCreate($user, 'create-sale');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Sale $model): bool
    {
        return false;
        //return CanUpdateHelper::canUpdate($user, $model, 'edit-sale')
         //   && $model->status === 'in-progress';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Sale $model): bool
    {
        return false;
        //return CanDeleteHelper::canDelete($user, $model, 'delete-sale');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Sale $model): bool
    {
        return CanRestoreHelper::canRestore($user, $model, 'restore-sale');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Sale $model): bool
    {
        return CanForceDeleteHelper::canForceDelete($user, $model, 'force-delete-sale');
    }
    
    public function confirm(User $user, Sale $model): bool
    {
        return CanConfirmHelper::canConfirm($user, $model, 'confirm-sale')
            && $model->status === 'in-progress';
    }
    
    public function cancel(User $user, Sale $model): bool
    {
        return CanCancelHelper::canCancel($user, $model, 'cancel-sale');
    }
}
