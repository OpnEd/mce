<?php

namespace App\Policies;

use App\Models\Api\ExternalOrder;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\Response;

class ExternalOrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ExternalOrder $externalOrder): bool
    {
        $currentTeamId = Filament::getTenant()->id;
            return $externalOrder->team_id === null || 
                $externalOrder->team_id === $currentTeamId;
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
    public function update(User $user, ExternalOrder $externalOrder): bool
    {
        return $externalOrder->claimed_by === $user->id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ExternalOrder $externalOrder): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ExternalOrder $externalOrder): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ExternalOrder $externalOrder): bool
    {
        return false;
    }
}
