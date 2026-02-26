<?php

namespace App\Filament\Resources\Quality\WasteGenerationReportResource\Pages;

use App\Filament\Resources\Quality\WasteGenerationReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWasteGenerationReport extends ViewRecord
{
    protected static string $resource = WasteGenerationReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
