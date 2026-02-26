<?php

namespace App\Filament\Resources\Quality\WasteGenerationReportResource\Pages;

use App\Filament\Resources\Quality\WasteGenerationReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWasteGenerationReports extends ListRecords
{
    protected static string $resource = WasteGenerationReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nuevo Informe'),
        ];
    }
}
