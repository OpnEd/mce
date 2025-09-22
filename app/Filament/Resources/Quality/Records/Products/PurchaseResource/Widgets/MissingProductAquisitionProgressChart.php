<?php

namespace App\Filament\Resources\Quality\Records\Products\PurchaseResource\Widgets;

use App\Services\Quality\Records\Products\MissingProductService;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class MissingProductAquisitionProgressChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'faltantesProgressChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Cómo vamos con los faltantes';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $missingProductService = app(MissingProductService::class);
        $progressData = $missingProductService->calculateProgress();
        //dd($progressData);

        return [
            'chart' => [
                'type' => 'radialBar',
                'height' => 300,
            ],
            'series' => [$progressData['aquisitionProgress']],
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
                'lineCap' => "round",
            ],
            'labels' => ['Progreso mes presente'],
            'colors' => ['#0833a2'],
            'legend' => [
                'labels' => [
                    'fontFamily' => 'inherit',
                ],
            ],
        ];
    }
}
