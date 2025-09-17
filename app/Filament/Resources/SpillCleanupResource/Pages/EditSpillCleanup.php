<?php

namespace App\Filament\Resources\SpillCleanupResource\Pages;

use App\Filament\Resources\SpillCleanupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpillCleanup extends EditRecord
{
    protected static string $resource = SpillCleanupResource::class;
    protected static ?string $title = 'Editar detalles de Limpieza de Derrame';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Eliminar Registro'),
        ];
    }
}
