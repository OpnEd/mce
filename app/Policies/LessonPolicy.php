<?php

namespace App\Policies;

use App\Helpers\CanCreateHelper;
use App\Helpers\CanDeleteHelper;
use App\Helpers\CanUpdateHelper;
use App\Models\Quality\Training\Lesson;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\Response;

class LessonPolicy
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
    public function view(User $user, Lesson $lesson): bool
    {
        $module = $lesson->module;

        // Aplicamos la misma lógica del curso:
        // Si el curso padre es global, puede ver el módulo.
        if ($module->course->team_id === null) {
            return true;
        }

        // Si el curso es propio, solo si el team_id coincide.
        return $module->course->team_id === Filament::getTenant()->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins and instructors can create lessons
        return CanCreateHelper::canCreate($user, 'create-lessons') && ($user->isAdmin() || $user->isInstructor());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Lesson $lesson): bool
    {
        return CanUpdateHelper::canUpdate($user, $lesson, 'edit-lessons') && $user->id === $lesson->instructor_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lesson $lesson): bool
    {
        return CanDeleteHelper::canDelete($user, $lesson, 'delete-lessons') && $user->id === $lesson->instructor_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Lesson $lesson): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Lesson $lesson): bool
    {
        return false;
    }
}
