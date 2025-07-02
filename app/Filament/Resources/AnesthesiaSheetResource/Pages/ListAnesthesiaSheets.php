<?php

namespace App\Filament\Resources\AnesthesiaSheetResource\Pages;

use App\Filament\Resources\AnesthesiaSheetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnesthesiaSheets extends ListRecords
{
    protected static string $resource = AnesthesiaSheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
