<?php

namespace App\Filament\Resources\AnesthesiaSheetResource\Pages;

use App\Filament\Resources\AnesthesiaSheetResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAnesthesiaSheet extends CreateRecord
{
    protected static string $resource = AnesthesiaSheetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
