<?php

namespace App\Filament\Resources\Quality\Records\Products\PurchaseResource\Widgets;

use App\Services\IndicatorService;
use App\Filament\Widgets\Concerns\HasIndicatorTooltip;
use Filament\Facades\Filament;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class MissingProductAquisitionProgressChart extends ApexChartWidget
{
    use HasIndicatorTooltip;
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
    protected static ?string $heading = 'Cuota permitida de faltantes / mes';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $teamId = Filament::getTenant()->id;
        $indicatorName = 'Adquisición';
        $data = app(IndicatorService::class)->getMonthlyCompliance($teamId, $indicatorName);

        return [
            'chart' => [
                'type' => 'radialBar',
                'height' => 200,
            ],
            'series' => [$data['progress']],
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

    protected function extraJsOptions(): ?\Filament\Support\RawJs
    {
        return $this->indicatorTooltipExtraJsOptionsFromIndicatorName('Adquisición');
    }
}
