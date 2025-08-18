<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Process Types
    |--------------------------------------------------------------------------
    |
    | Esta configuración define los tipos de procesos misionales según la
    | clasificación establecida (Planeación y Gerencia, Misionales u Operativos,
    | Apoyo, Evaluación y Seguimiento).
    |
    */
    'process_types' => [
        [
            'name' => 'Planeación y gerencia',
            'code' => 'D',
            'description' => 'Establecen la plataforma estratégica y deontológica de la organización',
        ],
        [
            'name' => 'Misionales u Operativos',
            'code' => 'M',
            'description' => 'Crean y desarrollan el producto o el servicio y lo entregan al usuario',
        ],
        [
            'name' => 'Apoyo',
            'code' => 'A',
            'description' => 'Brindan soporte al resto de los procesos para que estos puedan ser realizados efectivamente',
        ],
        [
            'name' => 'Evaluación y seguimiento',
            'code' => 'E',
            'description' => 'Miden los resultados de la ejecución de los procesos y analizan estos resultados para generar información que luego es utilizada por los procesos de planeación y gerencia y los procesos evaluados para el desarrollo y la ejecución de planes de mejora continua. Finalmente hacen seguimiento al cumplimiento de planes de mejora.',
        ],
    ],
];
