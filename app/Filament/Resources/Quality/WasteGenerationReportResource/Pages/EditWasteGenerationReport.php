<?php

namespace App\Filament\Resources\Quality\WasteGenerationReportResource\Pages;

use App\Filament\Resources\Quality\WasteGenerationReportResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditWasteGenerationReport extends EditRecord
{
    protected static string $resource = WasteGenerationReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Informe actualizado')
            ->body('Los cambios han sido guardados correctamente.')
            ->send();
    }
}
