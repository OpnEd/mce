<?php

namespace App\Filament\Resources\Quality\Records\Products\MissingProductResource\Pages;

use App\Filament\Resources\Quality\Records\Products\MissingProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMissingProducts extends ListRecords
{
    protected static string $resource = MissingProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Registrar faltante'),
        ];
    }

    public function getHeading(): string
    {
        return __('Lista de Faltantes');
    }
}