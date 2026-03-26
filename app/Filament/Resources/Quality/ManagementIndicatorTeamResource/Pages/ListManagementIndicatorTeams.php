<?php

namespace App\Filament\Resources\Quality\ManagementIndicatorTeamResource\Pages;

use App\Filament\Resources\Quality\ManagementIndicatorTeamResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;

class ListManagementIndicatorTeams extends ListRecords
{
    protected static string $resource = ManagementIndicatorTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\CreateAction::make()
                ->label('Tablero de indicadores')
                ->url(fn (): string => route('filament.admin.pages.dashboard', ['tenant' => Filament::getTenant()->id]))
                ->openUrlInNewTab(),
        ];
    }
}
