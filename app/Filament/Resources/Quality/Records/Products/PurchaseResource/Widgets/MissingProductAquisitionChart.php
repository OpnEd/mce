<?php

namespace App\Filament\Resources\Quality\Records\Products\PurchaseResource\Widgets;

use App\Services\IndicatorService;
use App\Models\Quality\Records\Products\MissingProduct;
use App\Services\Quality\Records\Products\MissingProductService;
use App\Filament\Widgets\Concerns\HasIndicatorTooltip;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class MissingProductAquisitionChart extends ApexChartWidget
{
    use HasIndicatorTooltip;
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'missingProductAquisitionChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Adquisición';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $teamId = Filament::getTenant()->id;
        
        // 1. Obtener configuración dinámica desde la BD (Meta definida en ManagementIndicatorTeam)
        // Asegúrate de que el nombre coincida con el de la base de datos (populate config)
        $indicatorName = 'Adquisición'; 
        $indicatorConfig = app(IndicatorService::class)->getIndicatorConfig($teamId, $indicatorName);

        // 2. Usar la meta de la BD, o fallback a 0 si no existe
        $metaValue = $indicatorConfig ? (float) $indicatorConfig->pivot->indicator_goal : 0;
        
        // Opcional: Usar descripción de la BD para el tooltip o subtítulo si se desea
        // $description = $indicatorConfig?->description;
        
        
        $data = Trend::query(
            MissingProduct::query()
                ->where('team_id', Filament::getTenant()->id)
                ->forAcquisitionIndicator()
        )
            ->between(
                start: Carbon::parse($this->filterFormData['date_start']),
                end: Carbon::parse($this->filterFormData['date_end']),
            )
            ->perMonth()
            ->count();
        //dd($data);

        // Generar los datos de la serie y definir colores condicionales para cada valor
        $seriesData = $data->map(function (TrendValue $value) use ($metaValue) {
            return [
                'x' => Carbon::parse($value->date)->format('Y-m'),
                'y' => $value->aggregate,
                'fillColor' => $value->aggregate >= $metaValue ? '#FF4560' : ($value->aggregate >= ($metaValue * 0.8) ? '#F59E0B' : '#00E396'),
            ];
        });

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 200,
            ],
            'series' => [
                [
                    'name' => 'Numero de faltantes (Adquisicion) / mes',
                    'data' => $seriesData,
                ],
            ],
            'xaxis' => [
                'title' => [
                    'text' => 'Mes',
                ],
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'title' => [
                    'text' => 'Conteo Faltantes',
                ],
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
                'min' => 0, // Ajusta el rango mínimo si es necesario
                'max' => max($metaValue, $data->max(fn(TrendValue $value) => $value->aggregate)) + 10,
            ],
            'colors' => ['#f59e0b'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'horizontal' => false,
                    //'distributed' => true, // Permite que cada barra tenga un color independiente
                ],
            ],
            // Aquí se añade la línea de meta
            'annotations' => [
                'yaxis' => [
                    [
                        'y' => $metaValue,
                        'borderColor' => '#001259',
                        'label' => [
                            'text' => 'Meta: máx. ' . $metaValue,
                            'style' => [
                                'color' => '#fff',
                                'background' => '#0833a2',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function extraJsOptions(): ?\Filament\Support\RawJs
    {
        return $this->indicatorTooltipExtraJsOptionsFromIndicatorName('Adquisición');
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('date_start')
                ->default(now()->subMonths(2)),
            DatePicker::make('date_end')
                ->default(now()->addDay()),
        ];
    }
}
