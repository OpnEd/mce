<?php

namespace App\Filament\Resources\EnvironmentalRecordResource\Pages;

use App\Filament\Resources\EnvironmentalRecordResource;
use App\Mail\MedicationInfoMail;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Mail;

class ListEnvironmentalRecords extends ListRecords
{
    protected static string $resource = EnvironmentalRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            /* Action::make('Enviar correo de prueba')
                ->icon('heroicon-o-paper-airplane')
                ->action(function () {
                    Mail::to('rialmon@gmail.com')->send(new MedicationInfoMail('Probando envío desde Filament'));
                    Notification::make()
                        ->title('Correo enviado correctamente.')
                        ->success()
                        ->send();
                }) */
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\EnvironmentalRecordResource\Widgets\TempChart::class,
            \App\Filament\Resources\EnvironmentalRecordResource\Widgets\HumChart::class,
        ];
    }
}
