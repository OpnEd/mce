<?php

namespace App\Support\Tenancy;

use Filament\Facades\Filament;

class ResolvesCurrentTeamId
{
    public static function resolve(): ?int
    {
        try {
            $tenantId = Filament::getTenant()?->getKey();

            if ($tenantId !== null) {
                return (int) $tenantId;
            }
        } catch (\Throwable $exception) {
        }

        $user = auth()->user();

        if ($user?->current_team_id !== null) {
            return (int) $user->current_team_id;
        }

        if ($user?->team_id !== null) {
            return (int) $user->team_id;
        }

        return null;
    }
}
