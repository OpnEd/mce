<?php

namespace App\Filament\Resources\Quality\Records\Products\ProductReturnResource\Widgets;

use App\Filament\Widgets\Concerns\HasIndicatorTooltip;
use App\Models\Quality\Records\Products\ProductReturn;
use App\Services\IndicatorService;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ProductReturnMonthlyTypeChart extends ApexChartWidget
{
    use HasIndicatorTooltip;

    protected static ?string $chartId = 'productReturnMonthlyTypeChart';

    protected static ?string $heading = 'Devoluciones por vencimiento o deterioro / mes';

    protected function getOptions(): array
    {
        $teamId = Filament::getTenant()->id;

        $dataVencimiento = Trend::query(
            ProductReturn::query()
                ->where('team_id', $teamId)
                ->where('type', 'vencimiento')
        )
            ->between(
                start: Carbon::parse($this->filterFormData['date_start']),
                end: Carbon::parse($this->filterFormData['date_end']),
            )
            ->perMonth()
            ->count();

        $dataDeterioro = Trend::query(
            ProductReturn::query()
                ->where('team_id', $teamId)
                ->where('type', 'deterioro_almacenamiento')
        )
            ->between(
                start: Carbon::parse($this->filterFormData['date_start']),
                end: Carbon::parse($this->filterFormData['date_end']),
            )
            ->perMonth()
            ->count();

        $vencimientoMap = $dataVencimiento->mapWithKeys(
            fn (TrendValue $value) => [Carbon::parse($value->date)->format('Y-m') => $value->aggregate]
        );
        $deterioroMap = $dataDeterioro->mapWithKeys(
            fn (TrendValue $value) => [Carbon::parse($value->date)->format('Y-m') => $value->aggregate]
        );

        $categories = $vencimientoMap->keys()
            ->merge($deterioroMap->keys())
            ->unique()
            ->sort()
            ->values();

        $seriesVencimiento = $categories->map(fn (string $month) => $vencimientoMap[$month] ?? 0);
        $seriesDeterioro = $categories->map(fn (string $month) => $deterioroMap[$month] ?? 0);
        $seriesTotal = $categories->map(
            fn (string $month) => ($vencimientoMap[$month] ?? 0) + ($deterioroMap[$month] ?? 0)
        );

        $indicatorConfig = app(IndicatorService::class)
            ->getIndicatorConfig($teamId, 'Devoluciones');
        $metaValue = $indicatorConfig?->pivot?->indicator_goal;
        $metaValue = is_numeric($metaValue) ? (float) $metaValue : null;

        $annotations = [];
        if ($metaValue !== null) {
            $annotations['yaxis'][] = [
                'y' => $metaValue,
                'borderColor' => '#ef4444',
                'strokeDashArray' => 4,
                'label' => [
                    'text' => 'Meta: ' . $metaValue,
                    'style' => [
                        'color' => '#fff',
                        'background' => '#ef4444',
                    ],
                ],
            ];
        }

        $maxSeriesValue = max($seriesTotal->max() ?? 0, $metaValue ?? 0);

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 200,
                'stacked' => true,
            ],
            'series' => [
                [
                    'name' => 'Vencimiento',
                    'data' => $seriesVencimiento,
                    'color' => '#f97316',
                ],
                [
                    'name' => 'Deterioro almacenamiento',
                    'data' => $seriesDeterioro,
                    'color' => '#0ea5e9',
                ],
                [
                    'name' => 'Total',
                    'type' => 'line',
                    'data' => $seriesTotal,
                    'color' => '#111827',
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
                'title' => [
                    'text' => 'Conteo',
                ],
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
                'min' => 0,
                'max' => $maxSeriesValue > 0 ? $maxSeriesValue + 2 : null,
            ],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'horizontal' => false,
                ],
            ],
            'stroke' => [
                'width' => [0, 0, 3],
            ],
            'markers' => [
                'size' => [0, 0, 4],
            ],
            'annotations' => $annotations,
        ];
    }

    protected function extraJsOptions(): ?\Filament\Support\RawJs
    {
        return $this->indicatorTooltipExtraJsOptionsFromIndicatorName('Devoluciones');
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
