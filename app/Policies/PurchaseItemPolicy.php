<?php

namespace App\Policies;

use App\Helpers\CanCreateHelper;
use App\Helpers\CanDeleteHelper;
use App\Helpers\CanForceDeleteHelper;
use App\Helpers\CanRestoreHelper;
use App\Helpers\CanUpdateHelper;
use App\Helpers\CanViewAnyHelper;
use App\Helpers\CanViewHelper;
use App\Models\PurchaseItem;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Validation\Rules\Can;

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
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PurchaseItem $model): bool
    {
        return CanDeleteHelper::canDelete($user, $model, 'delete-purchase-item');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PurchaseItem $model): bool
    {
        return CanRestoreHelper::canRestore($user, $model, 'restore-purchase-item');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PurchaseItem $model): bool
    {
        return CanForceDeleteHelper::canForceDelete($user, $model, 'force-delete-purchase-item');
    }
}
