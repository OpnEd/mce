<?php

namespace App\Filament\Resources\AnesthesiaSheetItemResource\Pages;

use App\Filament\Resources\AnesthesiaSheetItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnesthesiaSheetItem extends EditRecord
{
    protected static string $resource = AnesthesiaSheetItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
