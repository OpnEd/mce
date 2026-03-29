<?php

namespace App\Policies;

use App\Helpers\CanCreateHelper;
use App\Helpers\CanDeleteHelper;
use App\Helpers\CanUpdateHelper;
use App\Models\Quality\Training\Course;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\Response;

class CoursePolicy
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
    public function view(User $user, Course $course): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins and instructors can create courses
        return CanCreateHelper::canCreate($user, 'create-course') && ($user->isAdmin() || $user->isInstructor());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Course $course): bool
    {   // Instructors can only update their own courses
        return CanUpdateHelper::canUpdate($user, $course, 'edit-course') && $user->id === $course->instructor_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Course $course): bool
    {
        return CanDeleteHelper::canDelete($user, $course, 'delete-course') && $user->id === $course->instructor_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Course $course): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Course $course): bool
    {
        return false;
    }
}
