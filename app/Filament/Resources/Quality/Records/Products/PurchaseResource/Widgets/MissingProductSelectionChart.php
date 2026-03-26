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

class MissingProductSelectionChart extends ApexChartWidget
{
    use HasIndicatorTooltip;
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'missingProductSelectionChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Selección';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $teamId = Filament::getTenant()->id;

        // 1. Obtener configuración dinámica desde la BD
        // El nombre debe coincidir con el creado en los seeders/populate
        $indicatorName = 'Selección'; 
        $indicatorConfig = app(IndicatorService::class)->getIndicatorConfig($teamId, $indicatorName);
        
        // 2. Usar la meta de la BD
        $metaValue = $indicatorConfig ? (float) $indicatorConfig->pivot->indicator_goal : 0;
        
        $data = Trend::query(
            MissingProduct::query()
                ->where('team_id', Filament::getTenant()->id)
                ->forSelectionIndicator()
        )
            ->between(
                start: Carbon::parse($this->filterFormData['date_start']),
                end: Carbon::parse($this->filterFormData['date_end']),
            )
            ->perMonth()
            ->count();

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
                    'name' => 'Numero de faltantes (Seleccion) / mes',
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
        return $this->indicatorTooltipExtraJsOptionsFromIndicatorName('Selección');
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
