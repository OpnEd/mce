<?php

namespace App\Filament\Resources\EnvironmentalRecordResource\Pages;

use App\Filament\Resources\EnvironmentalRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEnvironmentalRecord extends EditRecord
{
    protected static string $resource = EnvironmentalRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
