<?php

namespace App\Filament\Resources\ProductReceptionResource\Widgets;

use App\Models\ProductReception;
use App\Models\Purchase;
use App\Filament\Widgets\Concerns\HasIndicatorTooltip;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ProductReceptionProgressChart extends ApexChartWidget
{
    use HasIndicatorTooltip;
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
        $teamId = Filament::getTenant()->id;
        $progressData = $this->calculateMonthlyProgress($teamId);

        return [
            'chart' => [
                'type' => 'radialBar',
                'height' => 200,
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

    protected function extraJsOptions(): ?\Filament\Support\RawJs
    {
        return $this->indicatorTooltipExtraJsOptionsFromIndicatorName('Recepción');
    }

    protected function calculateMonthlyProgress(int $teamId): int
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        $countRecepcion = ProductReception::where('team_id', $teamId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $countOrden = Purchase::where('team_id', $teamId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return $countOrden > 0
            ? intval(($countRecepcion / $countOrden) * 100)
            : 0;
    }
}
