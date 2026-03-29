<?php

namespace App\Policies;

use App\Helpers\CanCreateHelper;
use App\Helpers\CanUpdateHelper;
use App\Models\Quality\Training\Enrollment;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\Response;

class EnrollmentPolicy
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
    public function view(User $user, Enrollment $enrollment): bool
    {
        // Admin can view any enrollment
        if ($user->isAdmin() || $user->isInstructor()) {
            return true;
        }

        // Users can view their own enrollment
        if ($user->id === $enrollment->user_id) {
            return true;
        }

        // Instructors can view enrollments in their courses
        if ($user->can('view-enrollments') && $user->id === $enrollment->course->instructor_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return CanCreateHelper::canCreate($user, 'create-enrollment');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Enrollment $model): bool
    {
        // Default behavior with helper
        return CanUpdateHelper::canUpdate($user, $model, 'edit-enrollments') && $user->id === $model->course->instructor_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Enrollment $enrollment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Enrollment $enrollment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Enrollment $enrollment): bool
    {
        return false;
    }
}
