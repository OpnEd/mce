<?php

namespace App\Filament\Resources\SpillCleanupResource\Pages;

use App\Filament\Resources\SpillCleanupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpillCleanups extends ListRecords
{
    protected static string $resource = SpillCleanupResource::class;
    protected static ?string $title = 'Histórico de limpieza de derrames';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Registrar Limpieza de Derrame'),
        ];
    }
}
