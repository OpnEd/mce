<?php

/**
 * Configuración escalable para sesiones de capacitación misional.
 * Modifica $totalWeeks y $sessionsPerWeek para ajustar la cantidad de semanas/sesiones.
 */

use Carbon\Carbon;

return function ($baseDate = null) {
    $baseDate = $baseDate ?: now();
    $totalWeeks = 8;
    $sessionsPerWeek = 2;
    $sessionDurationHours = 3;

    // Definición de datos fijos por sesión
    $sessions = [
        // Semana 1
        [
            'name' => 'Misión, visión y política',
            'description' => 'Comprender la relación de la misión, visión y política de calidad con los procesos misionales.',
            'objective' => 'Identificar cómo estos elementos estratégicos guían la operación del servicio farmacéutico.',
            'color' => '#4CAF50',
            'icon' => 'phosphor-strategy',
        ],
        [
            'name' => 'Clasificación de procesos',
            'description' => 'Diferenciar procesos estratégicos, misionales y de soporte.',
            'objective' => 'Elaborar el mapa general del servicio farmacéutico.',
            'color' => '#4CAF50',
            'icon' => 'phosphor-tree-structure',
        ],
        // Semana 2
        [
            'name' => 'Proveedores y compras',
            'description' => 'Identificar criterios de selección y homologación de proveedores.',
            'objective' => 'Optimizar la adquisición de medicamentos e insumos.',
            'color' => '#2196F3',
            'icon' => 'phosphor-truck',
        ],
        [
            'name' => 'Registros y riesgos en compras',
            'description' => 'Registrar correctamente entradas y gestionar riesgos de abastecimiento.',
            'objective' => 'Garantizar trazabilidad y seguridad del inventario entrante.',
            'color' => '#2196F3',
            'icon' => 'phosphor-shopping-cart',
        ],
        // Semana 3
        [
            'name' => 'Recepción y control',
            'description' => 'Aplicar protocolos de recepción y verificación documental.',
            'objective' => 'Asegurar calidad y conformidad de productos recibidos.',
            'color' => '#FFC107',
            'icon' => 'phosphor-list-magnifying-glass',
        ],
        [
            'name' => 'Almacenamiento y riesgos',
            'description' => 'Organizar productos según FEFO, FIFO, temperatura y humedad.',
            'objective' => 'Prevenir deterioros y pérdidas de inventario.',
            'color' => '#FFC107',
            'icon' => 'phosphor-box-arrow-down',
        ],
        // Semana 4
        [
            'name' => 'Dispensación básica',
            'description' => 'Definir tipos de dispensación y requisitos normativos.',
            'objective' => 'Garantizar entrega segura y correcta de medicamentos.',
            'color' => '#9C27B0',
            'icon' => 'phosphor-hand-coins',
        ],
        [
            'name' => 'Atención y satisfacción',
            'description' => 'Ofrecer orientación al paciente y medir satisfacción.',
            'objective' => 'Fomentar uso racional de medicamentos y fidelizar clientes.',
            'color' => '#9C27B0',
            'icon' => 'phosphor-smiley',
        ],
        // Semana 5
        [
            'name' => 'Devoluciones',
            'description' => 'Gestionar devoluciones de clientes y proveedores.',
            'objective' => 'Garantizar trazabilidad y cumplimiento normativo.',
            'color' => '#FF5722',
            'icon' => 'phosphor-arrow-u-up-left',
        ],
        [
            'name' => 'Disposición final',
            'description' => 'Eliminar medicamentos vencidos o en mal estado.',
            'objective' => 'Evitar riesgos para la salud pública y el medio ambiente.',
            'color' => '#FF5722',
            'icon' => 'phosphor-trash',
        ],
        // Semana 6
        [
            'name' => 'Farmacovigilancia',
            'description' => 'Detectar, evaluar y reportar eventos adversos.',
            'objective' => 'Contribuir a la seguridad del paciente.',
            'color' => '#3F51B5',
            'icon' => 'phosphor-note',
        ],
        [
            'name' => 'Tecnovigilancia',
            'description' => 'Monitorear el desempeño y seguridad de dispositivos médicos.',
            'objective' => 'Prevenir incidentes por fallas tecnológicas.',
            'color' => '#3F51B5',
            'icon' => 'phosphor-syringe',
        ],
        // Semana 7
        [
            'name' => 'Indicadores',
            'description' => 'Definir indicadores clave de desempeño del servicio farmacéutico.',
            'objective' => 'Medir eficacia, eficiencia y calidad.',
            'color' => '#795548',
            'icon' => 'phosphor-chart-donut',
        ],
        [
            'name' => 'Seguimiento y mejora',
            'description' => 'Analizar resultados y aplicar acciones correctivas.',
            'objective' => 'Lograr mejora continua del servicio.',
            'color' => '#795548',
            'icon' => 'phosphor-check-square',
        ],
        // Semana 8
        [
            'name' => 'Taller integrador',
            'description' => 'Elaborar mapa de procesos y plan de mejora propios.',
            'objective' => 'Integrar conocimientos adquiridos en un proyecto aplicado.',
            'color' => '#388E3C',
            'icon' => 'phosphor-user-focus',
        ],
        [
            'name' => 'Auditoría interna',
            'description' => 'Realizar auditoría simulada del servicio farmacéutico.',
            'objective' => 'Evaluar cumplimiento y detectar oportunidades de mejora.',
            'color' => '#388E3C',
            'icon' => 'phosphor-magnifying-glass',
        ],
    ];

    $schedule = [];
    foreach ($sessions as $index => $session) {
        // Calcula la semana y el día del bloque siempre escalable
        $week = intdiv($index, $sessionsPerWeek);
        $dayOfWeek = $index % $sessionsPerWeek; // Por ejemplo, 0=lunes, 1=miércoles
        $starts_at = Carbon::parse($baseDate)->addWeeks($week)->addDays($dayOfWeek * 2)->setTime(9, 0);
        $ends_at = (clone $starts_at)->addHours($sessionDurationHours);

        $schedule[] = array_merge($session, [
            'starts_at' => $starts_at,
            'ends_at' => $ends_at,
        ]);
    }
    return $schedule;
};
