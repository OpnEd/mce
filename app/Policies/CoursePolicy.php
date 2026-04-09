<?php

namespace App\Policies;

use App\Models\Quality\Training\Course;
use App\Models\User;
use Filament\Facades\Filament;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Course $course): bool
    {
        $tenantId = Filament::getTenant()?->id;

        if ($course->team_id === null) {
            return true;
        }

        return $course->isVisibleToTeam($tenantId) && ($user->isAdmin() || $user->isInstructor());
    }

    public function create(User $user): bool
    {
        $tenant = Filament::getTenant();

        return $user->isSuperAdmin() || $user->isAdmin() || ($tenant && $user->isInstructor() 
            && $user->teams()->whereKey(Filament::getTenant()->id)->exists()
        );
    }

    public function update(User $user, Course $course): bool
    {
        $tenant = Filament::getTenant();

        return $course->isOwnedByTeam($tenant?->id)
            && ($user->isAdmin() 
                || ($user->isInstructor() && $user->id === $course->instructor_id));
    }

    public function delete(User $user, Course $course): bool
    {
        return $this->update($user, $course);
    }

    public function restore(User $user, Course $course): bool
    {
        return false;
    }

    public function forceDelete(User $user, Course $course): bool
    {
        return false;
    }
}
