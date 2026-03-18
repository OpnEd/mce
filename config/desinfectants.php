<?php
// config/desinfectants.php

return [
    [
        'name' => 'Alcohol Etílico 70%',
        'active_ingredient' => 'Etanol',
        'concentration' => '70%',
        'indications' => 'Desinfección de superficies no críticas, manos y piel. Uso general en droguerías para dispensación y Recepción técnica.',
        'level' => 'medio',
        'applicable_areas' => ['Recepción técnica', 'Dispensación', 'Administrativa', 'Almacenamiento'], // Recepción técnica, Dispensación, Administrativa
        'active' => true,
    ],
    [
        'name' => 'Alcohol Isopropílico 70%',
        'active_ingredient' => 'Isopropanol',
        'concentration' => '70%',
        'indications' => 'Desinfección rápida de superficies y equipos electrónicos en áreas administrativas.',
        'level' => 'medio',
        'applicable_areas' => ['Administrativa'], // Administrativa
        'active' => true,
    ],
    [
        'name' => 'Peróxido de Hidrógeno Estabilizado',
        'active_ingredient' => 'Peróxido de hidrógeno',
        'concentration' => '7.5%',
        'indications' => 'Desinfectante de alto nivel para instrumental termosensible e inmersión. Degrada en agua y oxígeno, ideal para áreas críticas.',
        'level' => 'alto',
        'applicable_areas' => ['Dispensación', 'Inyectología', 'Baño'], // Almacenamiento, Dispensación
        'active' => true,
    ],
    [
        'name' => 'Hipoclorito de Sodio',
        'active_ingredient' => 'Hipoclorito de sodio',
        'concentration' => '2500 ppm',
        'indications' => 'Lavado rutinario de superficies resistentes, pisos y áreas críticas y semicríticas. Requiere dilución y enjuague.',
        'level' => 'medio',
        'applicable_areas' => [
            'Recepción técnica',
            'Almacenamiento',
            'Dispensación',
            'Administrativa',
            'Cuarentena',
            'Rechazos y devoluciones',
        ], // Cuarentena, Rechazos
        'active' => true,
    ],
    [
        'name' => 'Hipoclorito de Sodio',
        'active_ingredient' => 'Hipoclorito de sodio',
        'concentration' => '5000 ppm',
        'indications' => 'Lavado terminal de superficies resistentes, pisos y áreas críticas y semicríticas. Requiere dilución y enjuague.',
        'level' => 'medio',
        'applicable_areas' => ['Baño', 'Inyectología'], // Cuarentena, Rechazos
        'active' => true,
    ],
    [
        'name' => 'Clorhexidina Gluconato',
        'active_ingredient' => 'Clorhexidina',
        'concentration' => '2-4%',
        'indications' => 'Antiséptico para superficies semicríticas y piel. Persistente y efectivo contra bacterias.',
        'level' => 'medio',
        'applicable_areas' => ['Dispensación', 'Inyectología'], // Recepción técnica, Almacenamiento, Dispensación
        'active' => true,
    ],
    [
        'name' => 'Amonio cuaternario',
        'active_ingredient' => 'Cloruro de benzalconio',
        'concentration' => '0.1-0.2%',
        'indications' => 'Desinfectante de superficies hospitalarias sin enjuague. Para uso en paños y spray.',
        'level' => 'medio',
        'applicable_areas' => ['Dispensación', 'Inyectología'], // Recepción técnica, Dispensación, Administrativa
        'active' => true,
    ],
    [
        'name' => 'Agua Oxigenada 3%',
        'active_ingredient' => 'Peróxido de hidrógeno',
        'concentration' => '3%',
        'indications' => 'Desinfección de superficies y heridas menores. Uso externo económico y accesible.',
        'level' => 'bajo',
        'applicable_areas' => ['Inyectología'], // Administrativa, Cuarentena, Rechazos
        'active' => true,
    ],
];

