<?php

namespace App\Filament\Resources\Quality\Records\Products\MissingProductResource\Pages;

use App\Filament\Resources\Quality\Records\Products\MissingProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMissingProducts extends ListRecords
{
    protected static string $resource = MissingProductResource::class;

    public function getHeaderWidgetsColumns(): int | array
    {
        return 3;
    }

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

    public function getSubheading(): ?string
    {
        return __('E indicadores de gestión de Selección y Adquisición');
    }
}