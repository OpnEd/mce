<?php

namespace App\Filament\Resources\EnvironmentalRecordResource\Pages;

use App\Filament\Resources\EnvironmentalRecordResource;
use App\Mail\MedicationInfoMail;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\CreateAction as PageCreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\CreateAction as TableCreateAction;
use Illuminate\Support\Facades\Mail;

class ListEnvironmentalRecords extends ListRecords
{
    protected static string $resource = EnvironmentalRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Registrar temperatura y humedad')
                ->icon('phosphor-thermometer-hot')
                ->modalHeading('Registrar temperatura y humedad')
                ->modalWidth('md')
                ->createAnother(false)
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->id();

                    return $data;
                }),
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

    protected function configureCreateAction(PageCreateAction | TableCreateAction $action): void
    {
        parent::configureCreateAction($action);

        // Evita el redirect a la página de creación y fuerza el modal.
        if ($action instanceof PageCreateAction) {
            $action->url(null);
        }
    }
}
