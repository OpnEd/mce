<?php

namespace App\Filament\Resources\EnvironmentalRecordResource\Widgets;

use App\Filament\Widgets\Concerns\HasIndicatorTooltip;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TemperatureHumidityChart extends ApexChartWidget
{
    use HasIndicatorTooltip;
    protected static ?string $heading = 'Temperature and Humidity Chart';

    protected function getData(): array
    {
        return [
            'series' => [
                [
                    'name' => 'Temperatura',
                    'data' => \App\Models\EnvironmentalRecord::orderBy('created_at')
                        ->pluck('temp')
                        ->map(fn($v) => (float) $v)
                        ->toArray(),
                ],
                [
                    'name' => 'Humedad',
                    'data' => \App\Models\EnvironmentalRecord::orderBy('created_at')
                        ->pluck('hum')
                        ->map(fn($v) => (float) $v)
                        ->toArray(),
                ],
            ],
            'categories' => \App\Models\EnvironmentalRecord::orderBy('created_at')
                ->pluck('created_at')
                ->map(fn($date) => $date->format('d-m-Y H:i'))
                ->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        $data = $this->getData();

        return [
            'chart' => [
                'type' => $this->getType(),
                'height' => 200,
            ],
            'series' => $data['series'] ?? [],
            'xaxis' => [
                'categories' => $data['categories'] ?? [],
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
        ];
    }

    protected function extraJsOptions(): ?\Filament\Support\RawJs
    {
        return $this->indicatorTooltipExtraJsOptionsFromIndicatorName('Almacenamiento - VAMT');
    }

    protected function getType(): string
    {
        return 'line';
    }
}
