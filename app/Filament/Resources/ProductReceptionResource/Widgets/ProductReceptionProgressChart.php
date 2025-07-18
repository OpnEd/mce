<?php

namespace App\Filament\Resources\ProductReceptionResource\Widgets;

use App\Services\IndicatorService;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ProductReceptionProgressChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'recepcionTProgress';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Avance mensual en Recepciones Técnicas';

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
        $progress = $recepcion->getMonthlyCompliance($teamId);

        $progressData = $progress['progress'];
        return [
            'chart' => [
                'type' => 'radialBar',
                'height' => 300,
            ],
            'series' => [$progressData],
            'plotOptions' => [
                'radialBar' => [
                    'hollow' => [
                        'size' => '70%',
                    ],
                    'dataLabels' => [
                        'show' => true,
                        'name' => [
                            'show' => true,
                            'fontFamily' => 'inherit'
                        ],
                        'value' => [
                            'show' => true,
                            'fontFamily' => 'inherit',
                            'fontWeight' => 600,
                            'fontSize' => '20px'
                        ],
                    ],

                ],
            ],
            'stroke' => [
                'lineCap' => 'round',
            ],
            'labels' => ['Progreso mes presente'],
            'colors' => ['#f59e0b'],
        ];
    }
}
