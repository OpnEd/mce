<?php

use Carbon\Carbon;

return function ($baseDate = null, array $context = []): array {
    $referenceDate = $baseDate ? Carbon::parse($baseDate) : now();
    $year = (int) $referenceDate->year;
    $yearStart = Carbon::create($year, 1, 1, 0, 0, 0);
    $yearEnd = Carbon::create($year, 12, 31, 23, 59, 59);

    $events = [];

    // Calibracion de termohigrometro: trimestral.
    foreach ([1, 4, 7, 10] as $month) {
        $date = Carbon::create($year, $month, 1, 0, 0, 0);
        while ($date->dayOfWeek !== Carbon::TUESDAY) {
            $date->addDay();
        }

        $events[] = [
            'title' => 'Calibracion de termohigrometro',
            'description' => 'Verificacion y calibracion trimestral del termohigrometro.',
            'type' => 'task',
            'start_date' => $date->toDateString(),
            'end_date' => $date->toDateString(),
            'has_time' => false,
            'start_time' => null,
            'end_time' => null,
        ];
    }

    // Mantenimiento de refrigeradores: semestral.
    foreach ([3, 9] as $month) {
        $date = Carbon::create($year, $month, 1, 0, 0, 0);
        while ($date->dayOfWeek !== Carbon::THURSDAY) {
            $date->addDay();
        }

        $events[] = [
            'title' => 'Mantenimiento de refrigeradores',
            'description' => 'Mantenimiento preventivo semestral de refrigeradores.',
            'type' => 'task',
            'start_date' => $date->toDateString(),
            'end_date' => $date->toDateString(),
            'has_time' => false,
            'start_time' => null,
            'end_time' => null,
        ];
    }

    // Recarga de extintor: anual.
    $extinguisherDate = Carbon::create($year, 6, 15, 0, 0, 0);
    $events[] = [
        'title' => 'Recarga de extintor',
        'description' => 'Recarga anual y verificacion de vigencia del extintor.',
        'type' => 'task',
        'start_date' => $extinguisherDate->toDateString(),
        'end_date' => $extinguisherDate->toDateString(),
        'has_time' => false,
        'start_time' => null,
        'end_time' => null,
    ];

    usort($events, function (array $left, array $right): int {
        return strcmp((string) ($left['start_date'] ?? ''), (string) ($right['start_date'] ?? ''));
    });

    return [
        'schedule' => [
            'name' => 'Cronograma de calibracion y mantenimiento de equipos',
            'slug' => 'cronograma-de-calibracion-y-mantenimiento-de-equipos',
            'description' => 'Calibraciones y mantenimientos de equipos criticos durante un ano.',
            'objective' => 'Garantizar operacion confiable de equipos y cumplimiento sanitario.',
            'starts_at' => $yearStart->toDateTimeString(),
            'ends_at' => $yearEnd->toDateTimeString(),
            'color' => '#F59E0B',
            'icon' => 'phosphor-wrench',
        ],
        'events' => $events,
    ];
};
