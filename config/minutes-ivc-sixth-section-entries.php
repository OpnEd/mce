<?php

use App\Models\MinutesIvcSectionEntry as EntryType;
use Illuminate\Support\Facades\Storage;

return [

    /*
    |--------------------------------------------------------------------------
    | Minutes IVC Sections
    |--------------------------------------------------------------------------
    */

    [
        'apply' => true,
        'entry_id' => '6.1',
        'criticality' => 'menor',
        'question' => '¿Cuentan con planeación estratégica documentada (misión, visión, política de calidad, objetivos de calidad)?.',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'planeacion-estrategica'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '6.2',
        'criticality' => 'menor',
        'question' => '¿Cuentan con un procedimiento de gestión documental que impida el uso accidental de documentos obsoletos o no aprobados?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'procedimiento-de-gestion-documental'
            ],
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '6.3',
        'criticality' => 'menor',
        'question' => '¿Cuentan con organigrama y manual de funciones del personal que labora en el establecimiento?.',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'manual-de-funciones'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '6.4',
        'criticality' => 'menor',
        'question' => '¿Los procesos propios del establecimiento farmacéutico se encuentran debidamente caracterizados?.',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'caracterizacion-de-seleccion'
            ],
            [
                'key' => 'document.slug',
                'value' => 'caracterizacion-de-adquisicion'
            ],
            [
                'key' => 'document.slug',
                'value' => 'caracterizacion-de-recepcion'
            
            ],
            [
                'key' => 'document.slug',
                'value' => 'caracterizacion-de-almacenamiento'

            ],
            [
                'key' => 'document.slug',
                'value' => 'caracterizacion-de-dispensacion'
            ],
            [
                'key' => 'document.slug',
                'value' => 'caracterizacion-de-devolucion'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '6.5',
        'criticality' => 'menor',
        'question' => '¿Cuenta con un mapa de procesos que represente los procesos estratégicos y críticos propios del Establecimiento?.',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'mapa-de-procesos'
            ],
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '6.6',
        'criticality' => 'menor',
        'question' => '¿Cuenta con procedimiento, cronograma y registros para la inducción y capacitación del personal?.',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'procedimiento-induccion-capacitacion'
            ],
            [
                'key' => 'record.route',
                'value' => 'filament.admin.resources.quality.schedules.index'
            ],
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '6.7',
        'criticality' => 'menor',
        'question' => '¿Cuenta con un procedimiento documentado para la medición de la satisfacción del usuario?.',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'procedimiento-medicion-satisfaccion-usuario'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '6.8',
        'criticality' => 'menor',
        'question' => '¿Cuenta con un procedimiento documentado y registros para el control, recepción, clasificación, evaluación y cierre de las quejas presentadas por los usuarios?.',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'procedimiento-quejas'
            ],
            [
                'key' => 'record.route',
                'value' => 'por.definir'
            ],
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '6.9',
        'criticality' => 'menor',
        'question' => '¿Cuenta con procedimiento y plan de auditoría interna?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
          'links' => [
            [
                'key' => 'document.slug',
                'value' => 'procedimiento-auditoria-interna'
            ],
            [
                'key' => 'schedule.route',
                'value' => 'plan-de-auditoria-interna'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '6.10',
        'criticality' => 'menor',
        'question' => '¿Cuenta con procedimiento para el desarrollo de planes de mejora, incluyendo los hallazgos de las visitas de la autoridad sanitaria?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'procedimiento-planes-de-mejora'
            ],
            [
                'key' => 'schedule.route',
                'value' => 'planes-de-mejora'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '6.11',
        'criticality' => 'menor',
        'question' => '¿Se evalúan y se mantienen bajo control los riesgos de mayor probabilidad de ocurrencia (Matriz de Riesgos)?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'evaluacion-y-gestion-de-riesgos'
            ],
            [
                'key' => 'document.slug',
                'value' => 'matriz-de-riesgos'
            ],
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '6.12',
        'criticality' => 'menor',
        'question' => '¿Realiza el seguimiento, análisis y medición de los procesos propios del establecimiento farmacéutico (indicadores de gestión)?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'page.route',
                'value' => 'filament.admin.pages.management-indicators'
            ]
        ],
        'compliance' => true,
    ],
];



