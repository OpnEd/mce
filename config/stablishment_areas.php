<?php
// config/stablishment_areas.php

return [
    [
        'name' => 'Recepción técnica',
        'description' => 'Recepción de productos enviados por el proveedor',
        'type' => 'semicritica',
        'frequency' => 'diaria',
        'active' => true,
    ],
    [
        'name' => 'Almacenamiento',
        'description' => 'Área para el almacenamiento de productos en estantes, vitrinas',
        'type' => 'semicritica',
        'frequency' => 'semanal',
        'active' => true,
    ],
    [
        'name' => 'Dispensación',
        'description' => 'Área para la dispensación de medicamentos y dispositivos médicos',
        'type' => 'semicritica',
        'frequency' => 'diaria',
        'active' => true,
    ],
    [
        'name' => 'Administrativa',
        'description' => 'Ubicación de elementos para realización de actividades de administración',
        'type' => 'bajo_riesgo',
        'frequency' => 'diaria',
        'active' => true,
    ],
    [
        'name' => 'Cuarentena',
        'description' => 'Almacenamiento de productos en cuarentena',
        'type' => 'bajo_riesgo',
        'frequency' => 'mensual',
        'active' => true,
    ],
    [
        'name' => 'Rechazos y devoluciones',
        'description' => 'Almacenamiento de productos que deben ser devueltos al proveedor por distintas razones',
        'type' => 'bajo_riesgo',
        'frequency' => 'mensual',
        'active' => true,
    ],
    [
        'name' => 'Baño',
        'description' => 'Área de ubicación de sanitario y lavamanos',
        'type' => 'critica',
        'frequency' => 'diaria',
        'active' => true,
    ],
    [
        'name' => 'Inyectología',
        'description' => 'Área para la administración de medicamentos inyectables',
        'type' => 'critica',
        'frequency' => 'diaria',
        'active' => true,
    ],
];
