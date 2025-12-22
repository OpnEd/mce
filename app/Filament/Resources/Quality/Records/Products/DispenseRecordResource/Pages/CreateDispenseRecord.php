<?php

namespace App\Filament\Resources\Quality\Records\Products\DispenseRecordResource\Pages;

use App\Filament\Resources\Quality\Records\Products\DispenseRecordResource;
use Filament\Notifications\Notification;
use App\Jobs\SendMedicationRationalUseEmailJob;
use Filament\Resources\Pages\CreateRecord;

class CreateDispenseRecord extends CreateRecord
{
    protected static string $resource = DispenseRecordResource::class;

    protected function afterCreate(): void
    {
        SendMedicationRationalUseEmailJob::dispatch($this->getRecord());

        Notification::make()
            ->title('Correo enviado con éxito')
            ->success()
            ->send();
            
    }

}
