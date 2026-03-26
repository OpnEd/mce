<?php

namespace App\Filament\Resources\Quality\Records\Products\DispenseRecordResource\Widgets;

use App\Models\Quality\Records\Clients\ClientSatisfactionEvaluation;
use App\Services\IndicatorService;
use App\Filament\Widgets\Concerns\HasIndicatorTooltip;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class UserSatisfactionChart extends ApexChartWidget
{
    use HasIndicatorTooltip;
    protected static ?string $chartId = 'userSatisfactionChart';
    protected static ?string $heading = 'Dispensación (Satisfaccion del Usuario)';

    protected function getOptions(): array
    {
        $teamId = Filament::getTenant()->id;

        $indicatorName = 'Dispensación - SU';
        $indicatorConfig = app(IndicatorService::class)->getIndicatorConfig($teamId, $indicatorName);
        $metaValue = $indicatorConfig ? (float) $indicatorConfig->pivot->indicator_goal : 4.5;

        $data = Trend::query(
            ClientSatisfactionEvaluation::query()
                ->where('team_id', $teamId)
                ->whereNotNull('evaluated_at')
        )
            ->between(
                start: Carbon::parse($this->filterFormData['date_start']),
                end: Carbon::parse($this->filterFormData['date_end']),
            )
            ->perMonth()
            ->dateColumn('evaluated_at')
            ->average('overall_score');

        $seriesData = $data->map(function (TrendValue $value) use ($metaValue) {
            $averageScore = round($value->aggregate, 2);
            return [
                'x' => Carbon::parse($value->date)->format('Y-m'),
                'y' => $averageScore,
                'fillColor' => $averageScore >= $metaValue ? '#00E396' : ($averageScore >= ($metaValue * 0.9) ? '#F59E0B' : '#FF4560'),
            ];
        });

        return [
            'chart' => ['type' => 'bar', 'height' => 200],
            'series' => [
                [
                    'name' => 'Puntaje Promedio',
                    'data' => $seriesData,
                ],
            ],
            'xaxis' => [
                'title' => ['text' => 'Mes'],
                'labels' => ['style' => ['fontFamily' => 'inherit']],
            ],
            'yaxis' => [
                'title' => ['text' => 'Puntaje Promedio (1-5)'],
                'labels' => ['style' => ['fontFamily' => 'inherit']],
                'min' => 0,
                'max' => 5,
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
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        return [
            DatePicker::make('date_start')->default($monthStart->subMonth(5)),
            DatePicker::make('date_end')->default($monthEnd),
        ];
    }
}
