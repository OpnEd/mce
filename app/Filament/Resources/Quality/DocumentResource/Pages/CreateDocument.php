<?php

namespace App\Filament\Resources\Quality\DocumentResource\Pages;

use App\Filament\Resources\Quality\DocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['prepared_by'] = auth()->id();
        return $data;
    }
}
