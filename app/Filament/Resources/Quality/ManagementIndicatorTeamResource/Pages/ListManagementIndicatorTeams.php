<?php

namespace App\Filament\Resources\Quality\ManagementIndicatorTeamResource\Pages;

use App\Filament\Resources\Quality\ManagementIndicatorTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManagementIndicatorTeams extends ListRecords
{
    protected static string $resource = ManagementIndicatorTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
