<?php

namespace App\Filament\Resources\EnvironmentalRecordResource\Widgets;

use App\Models\EnvironmentalRecord;
use App\Filament\Widgets\Concerns\HasIndicatorTooltip;
use Filament\Forms\Components\DatePicker;
use Flowframe\Trend\Trend;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TempChart extends ApexChartWidget
{
    //use HasIndicatorTooltip;
    //protected int | string | array $columnSpan = 'full';
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'tempChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Temperatura (°C)';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $data = Trend::model(EnvironmentalRecord::class)
            ->between(
                start: Carbon::parse($this->filterFormData['date_start']),
                end: Carbon::parse($this->filterFormData['date_end']),
            )
            ->perDay()
            ->average('temp');

        return [
            'chart' => [
                'type' => 'line',
                'height' => 200,
            ],
            'series' => [
                [
                    'name' => 'Temperatura (°C)',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'xaxis' => [
                'categories' => $data->map(fn (TrendValue $value) => $value->date),
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
            ],
            'colors' => ['#44aa33'],
            'stroke' => [
                'curve' => 'smooth',
            ],
        ];
    }

    /* protected function extraJsOptions(): ?\Filament\Support\RawJs
    {
        return $this->indicatorTooltipExtraJsOptionsFromIndicatorName('Almacenamiento - VAMT');
    } */

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('date_start')
                ->default(now()->subWeek()),
            DatePicker::make('date_end')
                ->default(now()),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
