<?php

namespace App\Filament\Resources\Quality\Records\Cleaning\CleaningRecordResource\Pages;

use App\Filament\Resources\Quality\Records\Cleaning\CleaningRecordResource;
use App\Models\Quality\Records\Cleaning\{CleaningRecord, StablishmentArea, Desinfectant};
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CleaningTableView extends Page
{
    protected static string $resource = CleaningRecordResource::class;

    protected static string $view = 'filament.pages.cleaning-table';

    protected static ?string $title = 'Tabla de Limpieza por Turnos';

    public $fecha_seleccionada;
    public $areas;
    public $desinfectants;
    public $registros_agrupados;
    public $total_registros;

    public function mount()
    {
        $this->fecha_seleccionada = now()->format('Y-m-d');
        $this->cargarDatos();
    }

    public function cargarDatos()
    {
        $this->areas = StablishmentArea::where('active', true)->orderBy('name')->get();
        $this->desinfectants = Desinfectant::where('active', true)->orderBy('name')->get();

        // Versión simplificada temporal
        $registros = CleaningRecord::whereDate('created_at', $this->fecha_seleccionada)->get();
        $this->registros_agrupados = collect(['full_day' => $registros]); // Agrupar todo como un solo turno
        $this->total_registros = $registros->count();
    }



    protected function getHeaderActions(): array
    {
        return [
            Action::make('cambiar_fecha')
                ->label('Cambiar Fecha')
                ->icon('phosphor-calendar')
                ->form([
                    Forms\Components\DatePicker::make('fecha')
                        ->default($this->fecha_seleccionada)
                        ->required()
                        ->native(false),
                ])
                ->action(function (array $data) {
                    $this->fecha_seleccionada = $data['fecha'];
                    $this->cargarDatos();
                })
                ->modalWidth('md'),

            Action::make('nuevo_registro')
                ->label('Nuevo Registro')
                ->icon('phosphor-plus-circle')
                ->url(fn() => CleaningRecordResource::getUrl('create'))
                ->color('success'),

            Action::make('vista_lista')
                ->label('Lista de Registros')
                ->icon('phosphor-list-bullets')
                ->url(fn() => CleaningRecordResource::getUrl('index'))
                ->color('gray'),

            /* Action::make('exportar_pdf')
                ->label('Exportar PDF')
                ->icon('phosphor-file-arrow-down')
                ->color('primary')
                ->action(function () {
                    // Implementar exportación PDF aquí
                    // Por ahora, solo mostrar notificación
                    \Filament\Notifications\Notification::make()
                        ->title('Exportación PDF')
                        ->body('Funcionalidad en desarrollo')
                        ->info()
                        ->send();
                }), */
        ];
    }

    public function estaLimpiada($registros, $areaId, $tipo = 'cleaned'): bool
    {
        return $registros->contains(function ($registro) use ($areaId, $tipo) {
            $areas = $registro->cleaned_areas ?? [];
            foreach ($areas as $area) {
                if ($area['area_id'] == $areaId && ($area[$tipo] ?? false)) {
                    return true;
                }
            }
            return false;
        });
    }

    public function desinfectanteUsado($registros, $desinfectantId): bool
    {
        return $registros->contains(function ($registro) use ($desinfectantId) {
            $areas = $registro->cleaned_areas ?? [];
            foreach ($areas as $area) {
                if (($area['desinfectant_id'] ?? null) == $desinfectantId) {
                    return true;
                }
            }
            return false;
        });
    }

    public function tieneObservaciones($registros, $areaId): string
    {
        $observaciones = collect();
        foreach ($registros as $registro) {
            foreach (($registro->cleaned_areas ?? []) as $areaReg) {
                if ($areaReg['area_id'] == $areaId && !empty($areaReg['area_observations'])) {
                    $observaciones->push($areaReg['area_observations']);
                }
            }
        }
        return $observaciones->implode('; ');
    }

    public function tienePlagas($registros, $areaId)
    {
        foreach ($registros as $registro) {
            foreach (($registro->cleaned_areas ?? []) as $areaReg) {
                if ($areaReg['area_id'] == $areaId && ($areaReg['search_evidence_pests'] ?? false)) {
                    return $areaReg['search_evidence_pests_observations'] ?? false;
                }
            }
        }
        return false;
    }

    public function getShiftStats()
    {
        $stats = [];

        try {
            foreach ($this->registros_agrupados as $shift => $registros) {
                $totalAreas = 0;
                $areasCompletadas = 0;

                foreach ($registros as $registro) {
                    if (!empty($registro->cleaned_areas) && is_array($registro->cleaned_areas)) {
                        $totalAreas += count($registro->cleaned_areas);

                        foreach ($registro->cleaned_areas as $area) {
                            if (isset($area['status']) && $area['status'] === 'completada') {
                                $areasCompletadas++;
                            }
                        }
                    }
                }

                $stats[$shift] = [
                    'registros' => $registros->count(),
                    'total_areas' => $totalAreas,
                    'areas_completadas' => $areasCompletadas,
                    'porcentaje' => $totalAreas > 0 ? round(($areasCompletadas / $totalAreas) * 100) : 0
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error en getShiftStats: ' . $e->getMessage());
            return [];
        }

        return $stats;
    }

    // Método corregido para verificar desinfectante en área específica
    public function desinfectanteUsadoEnArea($registros, $desinfectantId, $areaId): bool
    {
        return $registros->contains(function ($registro) use ($desinfectantId, $areaId) {
            $areas = $registro->cleaned_areas ?? [];
            foreach ($areas as $area) {
                // Verificar que sea la misma área Y el mismo desinfectante
                if (($area['area_id'] ?? null) == $areaId && ($area['desinfectant_id'] ?? null) == $desinfectantId) {
                    return true;
                }
            }
            return false;
        });
    }

    // Método para obtener cantidad usada en área específica
    public function getCantidadUsadaEnArea($registros, $areaId): string
    {
        foreach ($registros as $registro) {
            $areas = $registro->cleaned_areas ?? [];
            foreach ($areas as $area) {
                if (($area['area_id'] ?? null) == $areaId && !empty($area['amount_used'])) {
                    return $area['amount_used'];
                }
            }
        }
        return '';
    }

    // Método para obtener concentración usada en área específica
    public function getConcentracionUsadaEnArea($registros, $areaId): string
    {
        foreach ($registros as $registro) {
            $areas = $registro->cleaned_areas ?? [];
            foreach ($areas as $area) {
                if (($area['area_id'] ?? null) == $areaId && !empty($area['concentration'])) {
                    return $area['concentration'];
                }
            }
        }
        return '';
    }
}
