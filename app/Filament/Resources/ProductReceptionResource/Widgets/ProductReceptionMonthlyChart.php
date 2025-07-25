<?php

namespace App\Filament\Resources\ProductReceptionResource\Widgets;

use App\Models\ProductReception;
use App\Models\Purchase;
use App\Services\IndicatorService;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\DatePicker;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ProductReceptionMonthlyChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'recepcionTecnicaChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Recepciones técnicas / mes';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $recepcion = app(IndicatorService::class);
        $teamId = Filament::getTenant()->id;
        $indicator = 'Recepción Técnica';
        $indicador = $recepcion->getMonthlyCompliance($teamId, $indicator);

        $metaValue = $indicador['goal'];

        $dataR = Trend::query(
            ProductReception::query()
                ->where('team_id', $teamId)  // o la variable que uses
            )
            ->between(
                start: Carbon::parse($this->filterFormData['date_start']),
                end: Carbon::parse($this->filterFormData['date_end']),
            )
            ->perMonth()
            ->count();

            // Generar los datos de la serie
        $seriesR = $dataR->map(fn(TrendValue $value) => $value->aggregate);

        $dataO = Trend::query(
            Purchase::query()
                ->where('team_id', $teamId)  // o la variable que uses
            )
            ->between(
                start: Carbon::parse($this->filterFormData['date_start']),
                end: Carbon::parse($this->filterFormData['date_end']),
            )
            ->perMonth()
            ->count();

        // Generar los datos de la serie
        $seriesO = $dataO->map(fn(TrendValue $value) => $value->aggregate);

        // Fechas en el eje X (usando las fechas de `Faltante` como referencia)
        $categories = $dataO->map(fn(TrendValue $value) => Carbon::parse($value->date)->format('Y-m'));

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Órdenes',
                    'data' =>  $seriesO,
                    'color' => '#FF4560',
                ],
                [
                    'name' => 'Recepciones',
                    'data' =>  $seriesR,
                    'color' => '#00E396',
                ],
            ],
            'xaxis' => [
                'categories' => $categories,
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
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
                'title' => [
                    'text' => 'Conteo',
                ],
            ],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'horizontal' => false,
                    'columnWidth' => '50%',
                ],
            ],
            'legend' => [
                'position' => 'top',
                'horizontalAlign' => 'center',
                'labels' => [
                    'fontFamily' => 'inherit',
                ],
            ],
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('date_start')
                ->default(now()->subMonths(3)),
            DatePicker::make('date_end')
                ->default(now()->addDay()),
        ];
    }
}
