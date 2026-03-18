<?php

$allAreas = array_column(require __DIR__ . '/stablishment_areas.php', 'name');


return [
    //Escoba de uso general
    [
        'name' => 'Escoba uso general',
        'description' => 'Mango metálico, cerdas plásticas',
        'type' => 'reutilizable',
        'areas_use' => [
            'Recepción técnica',
            'Almacenamiento',
            'Dispensación',
            'Administrativa',
            'Cuarentena',
            'Rechazos y devoluciones',
        ],
        'active' => true,
    ],
    //Trapero de uso general
    [
        'name' => 'Trapero uso general',
        'description' => 'Mango metálico, fibras de algodón',
        'type' => 'reutilizable',
        'areas_use' => [
            'Recepción técnica',
            'Almacenamiento',
            'Dispensación',
            'Administrativa',
            'Cuarentena',
            'Rechazos y devoluciones',
        ],
        'active' => true,
    ],
    //Paño para estantes y vitrinas
    [
        'name' => 'Paño para estantes y vitrinas',
        'description' => 'Paño de microfibra o algodón, 20cm2',
        'type' => 'reutilizable',
        'areas_use' => [
            'Almacenamiento',
            'Dispensación',
        ],
        'active' => true,
    ],
    //Paño para techos y paredes
    [
        'name' => 'Paño para techos y paredes',
        'description' => 'Paño de microfibra o algodón, 20cm2',
        'type' => 'reutilizable',
        'areas_use' => [
            'Recepción técnica',
            'Almacenamiento',
            'Dispensación',
            'Administrativa',
            'Cuarentena',
            'Rechazos y devoluciones',
        ],
        'active' => true,
    ],
    //Paño para limpieza del baño
    [
        'name' => 'Paño para limpieza del baño',
        'description' => 'Paño de microfibra o algodón, 20cm2',
        'type' => 'reutilizable',
        'areas_use' => [
            'Baño',
        ],
        'active' => true,
    ],
    //Escoba inyectología
    [
        'name' => 'Escoba para el área de inyectología',
        'description' => 'Mango metálico, cerdas plásticas',
        'type' => 'reutilizable',
        'areas_use' => [
            'Inyectología'
        ],
        'active' => true,
    ],
    //Trapero inyectología
    [
        'name' => 'Trapero para el área de inyectología',
        'description' => 'Mango metálico, fibras de algodón',
        'type' => 'reutilizable',
        'areas_use' => [
            'Inyectología'
        ],
        'active' => true,
    ],
    //Paño inyectología
    [
        'name' => 'Paño para limpieza de inyectología',
        'description' => 'Paño de microfibra o algodón, 20cm2',
        'type' => 'reutilizable',
        'areas_use' => [
            'Inyectología'
        ],
        'active' => true,
    ],
];

