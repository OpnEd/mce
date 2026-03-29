<?php

namespace App\Policies;

use App\Helpers\CanCreateHelper;
use App\Helpers\CanUpdateHelper;
use App\Models\Quality\Training\Module;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\Response;

class ModulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Si el usuario está dentro de un Tenant, puede ver la lista
        return Filament::getTenant() !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Module $module): bool
    {
        $course = $module->course;

        // Aplicamos la misma lógica del curso:
        // Si el curso padre es global, puede ver el módulo.
        if ($course->team_id === null) {
            return true;
        }

        // Si el curso es propio, solo si el team_id coincide.
        return $course->team_id === Filament::getTenant()->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins and instructors can create modules
        return CanCreateHelper::canCreate($user, 'create-modules') && ($user->isAdmin() || $user->isInstructor());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Module $module): bool
    {
        // Instructors can only update modules in their own courses
        return CanUpdateHelper::canUpdate($user, $module, 'edit-modules') && $user->id === $module->course->instructor_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Module $module): bool
    {
        // Instructors cannot delete modules (they can only rearrange them)
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Module $module): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Module $module): bool
    {
        return true;
    }
}
