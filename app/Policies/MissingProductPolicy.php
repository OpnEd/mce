<?php

namespace App\Policies;

use App\Models\Quality\Records\Products\MissingProduct;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MissingProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MissingProduct $MissingProduct): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MissingProduct $MissingProduct): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MissingProduct $MissingProduct): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MissingProduct $MissingProduct): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MissingProduct $MissingProduct): bool
    {
        return true;
    }
}
