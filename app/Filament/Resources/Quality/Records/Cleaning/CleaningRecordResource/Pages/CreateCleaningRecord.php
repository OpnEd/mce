<?php

namespace App\Filament\Resources\Quality\Records\Cleaning\CleaningRecordResource\Pages;

use App\Filament\Resources\Quality\Records\Cleaning\CleaningRecordResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCleaningRecord extends CreateRecord
{
    protected static string $resource = CleaningRecordResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Datos registrados')
            ->body('La información de limpieza y desinfección fue registrada con éxito!');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
