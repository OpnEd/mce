<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Document Types
    |--------------------------------------------------------------------------
    |
    | Esta configuración define los tipos de documentos utilizados en el
    | sistema de gestión de calidad.
    |
    */
    'document_caterogies' => [
        [
            'name' => 'Manual',
            'code' => 'MN',
            'description' => 'Describe los fundamentos de alguna disciplina y se emplea como marco y justificación para el desarrollo de procesos y procedimientos.',
        ],
        [
            'name' => 'Caracterización de procesos',
            'code' => 'CT',
            'description' => 'Describe los principales rasgos de los procesos, sus componentes, desde sus proveedores con sus entradas hasta donde el cliente con las salidas. Señala los responsables, los indicadores de gestión, los recursos necesarios, puntos críticos, riesgos, entre otros.',
        ],
        [
            'name' => 'Indicador de gestión',
            'code' => 'IG',
            'description' => 'Describe al indicador de gestión, la fuente de los datos que emplea, las fórmulas matemáticas, su naturaleza y objetivos, su temporalidad, el responsable, entre otros.',
        ],
        [
            'name' => 'Procedimiento',
            'code' => 'PR',
            'description' => 'Describe el paso a paso de alguna tarea u operación, contextualizándola en primera instancia, señalando sus objetivos, su lugar en el mapa de procesos y la incidencia que tiene en el logro de los objetivos estratégicos.',
        ],
        [
            'name' => 'Instrucción',
            'code' => 'IN',
            'description' => 'Indicación sobre cómo operar puntualmente algún equipo o ejecutar alguna tarea puntual o mínima.',
        ],
        [
            'name' => 'Formulario',
            'code' => 'FM',
            'description' => 'Instrumento empleado para la recolección de información (registro) demandada por indicadores de gestión, actividades de evaluación, operaciones productivas, etc. Permite evidenciar la ejecución de tareas o procesos, o registrar el estado de las cosas en un momento determinado.',
        ],
        [
            'name' => 'Tabla o Matriz',
            'code' => 'TM',
            'description' => 'Presenta información tabulada.',
        ],
        [
            'name' => 'Gráficos e ilustraciones',
            'code' => 'GF',
            'description' => 'Diagramas, mapas, ilustraciones, etc.',
        ],
    ],
];
