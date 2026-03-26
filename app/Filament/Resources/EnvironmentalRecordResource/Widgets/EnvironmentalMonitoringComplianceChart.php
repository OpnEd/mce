<?php

namespace App\Filament\Resources\EnvironmentalRecordResource\Widgets;

use App\Models\EnvironmentalRecord;
use App\Services\IndicatorService;
use App\Filament\Widgets\Concerns\HasIndicatorTooltip;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class EnvironmentalMonitoringComplianceChart extends ApexChartWidget
{
    use HasIndicatorTooltip;
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'environmentalMonitoringComplianceChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Cumplimiento Ambiental';

    // Rangos aceptables para el cálculo. Podrían moverse a un archivo de configuración.
    protected const TEMP_MIN = 15;
    protected const TEMP_MAX = 25;
    protected const HUM_MIN = 40;
    protected const HUM_MAX = 60;

    protected function getOptions(): array
    {
        $teamId = Filament::getTenant()->id;

        $indicatorName = 'Almacenamiento - VADR';
        $indicatorConfig = app(IndicatorService::class)->getIndicatorConfig($teamId, $indicatorName);
        $metaValue = $indicatorConfig ? (float) $indicatorConfig->pivot->indicator_goal : 95.0; // Meta por defecto: 95%

        $data = Trend::query(
            EnvironmentalRecord::query()->where('team_id', $teamId)
        )
            ->between(
                start: Carbon::parse($this->filterFormData['date_start']),
                end: Carbon::parse($this->filterFormData['date_end']),
            )
            ->perMonth()
            ->average('CASE WHEN temp BETWEEN ' . self::TEMP_MIN . ' AND ' . self::TEMP_MAX . ' AND hum BETWEEN ' . self::HUM_MIN . ' AND ' . self::HUM_MAX . ' THEN 1 ELSE 0 END * 100');

        $seriesData = $data->map(function (TrendValue $value) use ($metaValue) {
            $percentage = round($value->aggregate, 2);
            return [
                'x' => Carbon::parse($value->date)->format('Y-m'),
                'y' => $percentage,
                'fillColor' => $percentage >= $metaValue ? '#00E396' : ($percentage >= ($metaValue * 0.9) ? '#F59E0B' : '#FF4560'),
            ];
        });

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 200
                ],
            'series' => [['name' => 'Cumplimiento (%)', 'data' => $seriesData]],
            'xaxis' => [
                'title' => ['text' => 'Mes'],
                'labels' => ['style' => ['fontFamily' => 'inherit']],
            ],
            'yaxis' => [
                'title' => ['text' => '% Cumplimiento'],
                'labels' => ['style' => ['fontFamily' => 'inherit']],
                'min' => 0,
                'max' => 100,
            ],
            'colors' => ['#00E396'],
            'plotOptions' => ['bar' => ['borderRadius' => 3, 'horizontal' => false]],
            'annotations' => [
                'yaxis' => [
                    [
                        'y' => $metaValue,
                        'borderColor' => '#001259',
                        'label' => [
                            'text' => 'Meta: ' . $metaValue . '%',
                            'style' => ['color' => '#fff', 'background' => '#0833a2'],
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function extraJsOptions(): ?\Filament\Support\RawJs
    {
        return $this->indicatorTooltipExtraJsOptionsFromIndicatorName('Almacenamiento - VADR');
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('date_start')->default(now()->subMonths(3)),
            DatePicker::make('date_end')->default(now()->addDay()),
        ];
    }
}
