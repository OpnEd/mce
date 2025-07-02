<?php

namespace App\Filament\Resources\EnvironmentalRecordResource\Pages;

use App\Filament\Resources\EnvironmentalRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEnvironmentalRecord extends CreateRecord
{
    protected static string $resource = EnvironmentalRecordResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
