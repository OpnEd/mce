<?php

namespace App\Policies;

use App\Helpers\CanCreateHelper;
use App\Helpers\CanDeleteHelper;
use App\Helpers\CanForceDeleteHelper;
use App\Helpers\CanRestoreHelper;
use App\Helpers\CanUpdateHelper;
use App\Helpers\CanViewAnyHelper;
use App\Helpers\CanViewHelper;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SaleItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return CanViewAnyHelper::canViewAny($user, 'view-sale-item');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SaleItem $model): bool
    {
        return CanViewHelper::canView($user, $model, 'view-sale-item');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return CanCreateHelper::canCreate($user, 'create-sale-item');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SaleItem $model): bool
    {
        // Check if the parent Sale is in 'in-progress' status
        if ($model->sale && $model->sale->status !== 'in-progress') {
            return false;
        }
        return CanUpdateHelper::canUpdate($user, $model, 'edit-sale-item');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SaleItem $model): bool
    {
        // Check if the parent Sale is in 'in-progress' status
        if ($model->sale && $model->sale->status !== 'in-progress') {
            return false;
        }
        return CanDeleteHelper::canDelete($user, $model, 'delete-sale-item');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SaleItem $model): bool
    {
        // Check if the parent Sale is in 'in-progress' status
        if ($model->sale && $model->sale->status !== 'in-progress') {
            return false;
        }
        return CanRestoreHelper::canRestore($user, $model, 'restore-sale-item');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SaleItem $model): bool
    {
        // Check if the parent Sale is in 'in-progress' status
        if ($model->sale && $model->sale->status !== 'in-progress') {
            return false;
        }
        return CanForceDeleteHelper::canForceDelete($user, $model, 'force-delete-sale-item');
    }
}
