<?php

namespace App\Filament\Resources\AnesthesiaSheetItemResource\Pages;

use App\Filament\Resources\AnesthesiaSheetItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnesthesiaSheetItems extends ListRecords
{
    protected static string $resource = AnesthesiaSheetItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
