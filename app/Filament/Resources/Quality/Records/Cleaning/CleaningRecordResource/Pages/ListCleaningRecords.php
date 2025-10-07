<?php

namespace App\Filament\Resources\Quality\Records\Cleaning\CleaningRecordResource\Pages;

use App\Filament\Resources\Quality\Records\Cleaning\CleaningRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCleaningRecords extends ListRecords
{
    protected static string $resource = CleaningRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            
            // Nuevo botón para la vista tabla
            Actions\Action::make('vista_tabla')
                ->label('Vista Tabla')
                ->icon('heroicon-o-table-cells')
                ->url(fn () => CleaningRecordResource::getUrl('table'))
                ->color('success'),
        ];
    }
}
