<?php

namespace App\Filament\TenantManager\Resources\TeamResource\Pages;

use App\Filament\TenantManager\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTeam extends ViewRecord
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
