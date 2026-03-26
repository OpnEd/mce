<?php

use Carbon\Carbon;

return function ($baseDate = null, array $context = []): array {
    $referenceDate = $baseDate ? Carbon::parse($baseDate) : now();
    $year = (int) $referenceDate->year;
    $yearStart = Carbon::create($year, 1, 1, 0, 0, 0);
    $yearEnd = Carbon::create($year, 12, 31, 23, 59, 59);

    $rawSections = is_array($context['sections'] ?? null) ? $context['sections'] : [];
    $sections = [];

    foreach ($rawSections as $section) {
        if (! is_array($section)) {
            continue;
        }

        $name = trim((string) ($section['name'] ?? ''));
        $status = (int) ($section['status'] ?? 1);
        if ($name === '' || $status !== 1) {
            continue;
        }

        $sections[] = [
            'name' => $name,
            'order' => (string) ($section['order'] ?? ''),
            'slug' => (string) ($section['slug'] ?? ''),
        ];
    }

    if (empty($sections)) {
        $sections[] = [
            'name' => 'Seccion IVC',
            'order' => '',
            'slug' => '',
        ];
    }

    usort($sections, function (array $left, array $right): int {
        return ((int) ($left['order'] ?? 0)) <=> ((int) ($right['order'] ?? 0));
    });

    $events = [];
    $cursor = $yearStart->copy();
    while ($cursor->dayOfWeek !== Carbon::MONDAY) {
        $cursor->addDay();
    }

    $sectionCount = count($sections);
    $index = 0;

    while ($cursor->lte($yearEnd)) {
        $section = $sections[$index % $sectionCount];
        $sectionName = $section['name'];
        $sectionOrder = trim((string) ($section['order'] ?? ''));
        $sectionSlug = trim((string) ($section['slug'] ?? ''));

        $description = 'Auditoria interna semanal enfocada en la seccion IVC: ' . $sectionName . '.';
        if ($sectionOrder !== '') {
            $description .= ' Orden de seccion: ' . $sectionOrder . '.';
        }
        if ($sectionSlug !== '') {
            $description .= ' Referencia: ' . $sectionSlug . '.';
        }

        $events[] = [
            'title' => 'Auditoria IVC - ' . $sectionName,
            'description' => $description,
            'type' => 'task',
            'start_date' => $cursor->toDateString(),
            'end_date' => $cursor->toDateString(),
            'has_time' => false,
            'start_time' => null,
            'end_time' => null,
        ];

        $cursor->addWeek();
        $index++;
    }

    return [
        'schedule' => [
            'name' => 'Cronograma de auditoria interna IVC',
            'slug' => 'cronograma-de-auditoria-interna-ivc',
            'description' => 'Auditoria interna semanal por secciones IVC durante un ano.',
            'objective' => 'Verificar de forma continua el cumplimiento de las secciones IVC en la drogueria.',
            'starts_at' => $yearStart->toDateTimeString(),
            'ends_at' => $yearEnd->toDateTimeString(),
            'color' => '#0EA5E9',
            'icon' => 'phosphor-clipboard-text',
        ],
        'events' => $events,
    ];
};
