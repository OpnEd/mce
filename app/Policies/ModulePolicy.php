<?php

namespace App\Policies;

use App\Models\Quality\Training\Module;
use App\Models\User;
use Filament\Facades\Filament;

class ModulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || Filament::getTenant() !== null;
    }

    public function view(User $user, Module $module): bool
    {
        return ($module->course?->isVisibleToTeam(Filament::getTenant()?->id) ?? false)
            && ($user->isAdmin() || $user->isInstructor());
    }

    public function create(User $user): bool
    {
        $tenantId = Filament::getTenant()?->id;

        return $tenantId !== null
            && ($user->isAdmin() || $user->isInstructor())
            && $user->teams()->whereKey($tenantId)->exists();
    }

    public function update(User $user, Module $module): bool
    {
        $tenantId = Filament::getTenant()?->id;

        return ($module->course?->isOwnedByTeam($tenantId) ?? false)
            && ($user->isAdmin() || ($user->isInstructor() && $user->id === $module->course?->instructor_id));
    }

    public function delete(User $user, Module $module): bool
    {
        return $this->update($user, $module);
    }

    public function restore(User $user, Module $module): bool
    {
        return false;
    }

    public function forceDelete(User $user, Module $module): bool
    {
        return false;
    }
}
