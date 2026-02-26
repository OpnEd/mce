<?php

namespace App\Filament\Resources\WasteResource\Pages;

use App\Filament\Resources\WasteResource;
use Filament\Actions;
use App\Models\Quality\WasteGenerationReport;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListWastes extends ListRecords
{
    protected static string $resource = WasteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getActionGenerarInformeAnioAnterior(),
            Actions\CreateAction::make()
                ->label('Nuevo registro de Residuos'),
        ];
    }

    /**
     * Acción: Generar informe del año anterior
     */
    protected function getActionGenerarInformeAnioAnterior(): Actions\Action
    {
        return Actions\Action::make('generar_informe_ano_anterior')
            ->label('Generar Informe del Año Anterior')
            ->color('success')
            ->icon('heroicon-m-document-chart-bar')
            ->action(function () {
                try {
                    $anio = WasteGenerationReport::getAnioAnterior();
                    $teamId = Filament::getTenant()->id;
                    $userId = Auth::user()->id;

                    // Verificar si ya existe informe para ese año
                    $existente = WasteGenerationReport::where('anio', $anio)
                        ->where('team_id', $teamId)
                        ->exists();

                    if ($existente) {
                        Notification::make()
                            ->warning()
                            ->title('Informe ya existe')
                            ->body("Ya existe un informe para el año {$anio}.")
                            ->send();
                        return;
                    }

                    // Generar informe
                    $informe = WasteGenerationReport::generarInformeDelAnio($anio, $teamId, $userId);

                    // Validar que tenga datos
                    if (!$informe->tieneValidez()) {
                        Notification::make()
                            ->warning()
                            ->title('Informe sin datos')
                            ->body("No hay registros de residuos para el año {$anio}.")
                            ->send();
                        return;
                    }

                    Notification::make()
                        ->success()
                        ->title('Informe generado')
                        ->body("Informe {$informe->numero_informe} creado exitosamente con {$informe->cantidad_registros} registros.")
                        ->persistent()
                        ->send();

                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Error al generar informe')
                        ->body($e->getMessage())
                        ->send();
                }
            })
            ->requiresConfirmation()
            ->modalHeading('Generar Informe de Residuos')
            ->modalDescription(fn () => sprintf(
                'Se generará un informe consolidado con todos los residuos del año %d. ¿Deseas continuar?',
                WasteGenerationReport::getAnioAnterior()
            ))
            ->modalSubmitActionLabel('Sí, generar informe')
            ->modalCancelActionLabel('Cancelar');
    }
}
