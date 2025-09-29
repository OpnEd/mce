<?php

namespace App\Filament\Resources\Quality\ManagementIndicatorTeamResource\Pages;

use App\Filament\Resources\Quality\ManagementIndicatorTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManagementIndicatorTeam extends EditRecord
{
    protected static string $resource = ManagementIndicatorTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
