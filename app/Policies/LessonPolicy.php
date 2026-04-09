<?php

namespace App\Policies;

use App\Models\Quality\Training\Lesson;
use App\Models\User;
use Filament\Facades\Filament;

class LessonPolicy
{
    public function viewAny(User $user): bool
    {
        return Filament::getTenant() !== null;
    }

    public function view(User $user, Lesson $lesson): bool
    {
        return $lesson->module?->course?->isVisibleToTeam(Filament::getTenant()?->id) ?? false;
    }

    public function create(User $user): bool
    {
        $tenantId = Filament::getTenant()?->id;

        return $tenantId !== null
            && ($user->isAdmin() || $user->isInstructor())
            && $user->teams()->whereKey($tenantId)->exists();
    }

    public function update(User $user, Lesson $lesson): bool
    {
        $course = $lesson->module?->course;

        return ($course?->isOwnedByTeam(Filament::getTenant()?->id) ?? false)
            && ($user->isAdmin() || ($user->isInstructor() && $user->id === $course?->instructor_id));
    }

    public function delete(User $user, Lesson $lesson): bool
    {
        return $this->update($user, $lesson);
    }

    public function restore(User $user, Lesson $lesson): bool
    {
        return false;
    }

    public function forceDelete(User $user, Lesson $lesson): bool
    {
        return false;
    }
}
