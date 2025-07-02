<?php

namespace App\Filament\Resources\EnvironmentalRecordResource\Widgets;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TemperatureHumidityChart extends ApexChartWidget
{
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

    protected function getType(): string
    {
        return 'line';
    }
}
