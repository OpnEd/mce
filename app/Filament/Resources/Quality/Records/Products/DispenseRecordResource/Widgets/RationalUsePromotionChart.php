<?php

namespace App\Filament\Resources\Quality\Records\Products\DispenseRecordResource\Widgets;

use App\Models\Quality\Records\Products\DispenseRecord;
use App\Services\IndicatorService;
use App\Filament\Widgets\Concerns\HasIndicatorTooltip;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class RationalUsePromotionChart extends ApexChartWidget
{
    use HasIndicatorTooltip;
    protected static ?string $chartId = 'rationalUsePromotionChart';
    protected static ?string $heading = 'Dispensación (Promoción del Uso Racional)';

    protected function getOptions(): array
    {
        $teamId = Filament::getTenant()->id;

        $indicatorName = 'Dispensación - PUR';
        $indicatorConfig = app(IndicatorService::class)->getIndicatorConfig($teamId, $indicatorName);
        // Asumimos que la meta es un número de interacciones por mes
        $metaValue = $indicatorConfig ? (float) $indicatorConfig->pivot->indicator_goal : 50;

        // NOTA: Se asume que el modelo 'DispenseRecord' existe con una columna 'team_id'.
        $data = Trend::query(
            DispenseRecord::query()->where('team_id', $teamId)
        )
            ->between(
                start: Carbon::parse($this->filterFormData['date_start']),
                end: Carbon::parse($this->filterFormData['date_end']),
            )
            ->perMonth()
            ->count();

        $seriesData = $data->map(function (TrendValue $value) use ($metaValue) {
            return [
                'x' => Carbon::parse($value->date)->format('Y-m'),
                'y' => $value->aggregate,
                'fillColor' => $value->aggregate >= $metaValue ? '#00E396' : ($value->aggregate >= ($metaValue * 0.8) ? '#F59E0B' : '#FF4560'),
            ];
        });

        return [
            'chart' => ['type' => 'bar', 'height' => 200],
            'series' => [
                [
                    'name' => 'Nº de Registros de Promoción',
                    'data' => $seriesData,
                ],
            ],
            'xaxis' => [
                'title' => ['text' => 'Mes'],
                'labels' => ['style' => ['fontFamily' => 'inherit']],
            ],
            'yaxis' => [
                'title' => ['text' => 'Cantidad de Registros'],
                'labels' => ['style' => ['fontFamily' => 'inherit']],
                'min' => 0,
                'max' => max($metaValue, $data->max(fn (TrendValue $value) => $value->aggregate)) + 10,
            ],
            'colors' => ['#00E396'],
            'plotOptions' => ['bar' => ['borderRadius' => 3, 'horizontal' => false]],
            'annotations' => [
                'yaxis' => [
                    [
                        'y' => $metaValue,
                        'borderColor' => '#001259',
                        'label' => [
                            'text' => 'Meta: ' . $metaValue,
                            'style' => ['color' => '#fff', 'background' => '#0833a2'],
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function extraJsOptions(): ?\Filament\Support\RawJs
    {
        return $this->indicatorTooltipExtraJsOptionsFromIndicatorName('Dispensación');
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('date_start')->default(now()->subMonths(2)),
            DatePicker::make('date_end')->default(now()->addDay()),
        ];
    }
}
