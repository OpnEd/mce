<?php

namespace App\Filament\Resources\Quality\WasteGenerationReportResource\Pages;

use App\Filament\Resources\InformeResource;
use App\Models\Informe;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Filament\Resources\Quality\WasteGenerationReportResource;
use App\Models\Quality\WasteGenerationReport;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;

class CreateWasteGenerationReport extends CreateRecord
{
    protected static string $resource = WasteGenerationReportResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;
        $data['team_id'] = Filament::getTenant()->id;

        // Generar número de informe
        $data['numero_informe'] = WasteGenerationReport::generarNumeroInforme(
            $data['anio'],
            $data['team_id']
        );

        return $data;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Informe creado')
            ->body('El informe ha sido creado exitosamente.')
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
