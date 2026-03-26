<?php

use Carbon\Carbon;

return function ($baseDate = null, array $context = []): array {
    $referenceDate = $baseDate ? Carbon::parse($baseDate) : now();
    $year = (int) $referenceDate->year;
    $yearStart = Carbon::create($year, 1, 1, 0, 0, 0);
    $yearEnd = Carbon::create($year, 12, 31, 23, 59, 59);

    $events = [];

    for ($month = 1; $month <= 12; $month++) {
        $serviceDate = Carbon::create($year, $month, 1, 0, 0, 0);
        while ($serviceDate->dayOfWeek !== Carbon::MONDAY) {
            $serviceDate->addDay();
        }

        $events[] = [
            'title' => 'Limpieza de techos',
            'description' => 'Limpieza profunda mensual de techos en areas de la drogueria.',
            'type' => 'task',
            'start_date' => $serviceDate->toDateString(),
            'end_date' => $serviceDate->toDateString(),
            'has_time' => true,
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
        ];

        $events[] = [
            'title' => 'Correr vitrinas y estantes',
            'description' => 'Movimiento y limpieza profunda mensual detras de vitrinas y estantes.',
            'type' => 'task',
            'start_date' => $serviceDate->toDateString(),
            'end_date' => $serviceDate->toDateString(),
            'has_time' => true,
            'start_time' => '10:30:00',
            'end_time' => '12:00:00',
        ];
    }

    return [
        'schedule' => [
            'name' => 'Cronograma de limpieza profunda',
            'slug' => 'cronograma-de-limpieza-profunda',
            'description' => 'Actividades mensuales de limpieza de techos y limpieza detras de vitrinas/estantes.',
            'objective' => 'Sostener condiciones sanitarias adecuadas mediante limpieza profunda mensual.',
            'starts_at' => $yearStart->toDateTimeString(),
            'ends_at' => $yearEnd->toDateTimeString(),
            'color' => '#16A34A',
            'icon' => 'phosphor-broom',
        ],
        'events' => $events,
    ];
};
