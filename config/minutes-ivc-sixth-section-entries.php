<?php

use App\Models\MinutesIvcSectionEntry as EntryType;
use Illuminate\Support\Facades\Storage;

return [

    /*
    |--------------------------------------------------------------------------
    | Minutes IVC Sections
    |--------------------------------------------------------------------------
    */
//1
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
            ],
            [
                'key' => 'page.route',
                'value' => 'filament.admin.pages.planeacion-estrategica'
            ]
        ],
        'compliance' => true,
    ],
    //2
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
                'value' => 'gestion-documental'
            ],
        ],
        'compliance' => true,
    ],
    //3
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
            ],
            [
                'key' => 'page.route',
                'value' => 'filament.admin.pages.plataforma-estrategica-archivos'
            ]
        ],
        'compliance' => true,
    ],
    //4
    [
        'apply' => true,
        'entry_id' => '6.4',
        'criticality' => 'menor',
        'question' => '¿Los procesos propios del establecimiento farmacéutico se encuentran debidamente caracterizados?.',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'characterization.slug',
                'value' => 'seleccion'
            ],
            [
                'key' => 'characterization.slug',
                'value' => 'adquisicion'
            ],
            [
                'key' => 'characterization.slug',
                'value' => 'recepcion'
            
            ],
            [
                'key' => 'characterization.slug',
                'value' => 'almacenamiento'

            ],
            [
                'key' => 'characterization.slug',
                'value' => 'dispensacion'
            ],
            [
                'key' => 'characterization.slug',
                'value' => 'devolucion'
            ]
        ],
        'compliance' => true,
    ],
//5
    [
        'apply' => true,
        'entry_id' => '6.5',
        'criticality' => 'menor',
        'question' => '¿Cuenta con un mapa de procesos que represente los procesos estratégicos y críticos propios del Establecimiento?.',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'page.route',
                'value' => 'filament.admin.pages.plataforma-estrategica-archivos'
            ],
        ],
        'compliance' => true,
    ],
    //6
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
                'value' => 'induccion-capacitacion'
            ],
            [
                'key' => 'schedule.route',
                'value' => 'cronograma-de-capacitaciones'
            ],
        ],
        'compliance' => true,
    ],
    //7
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
                'value' => 'medicion-satisfaccion-usuario'
            ]
        ],
        'compliance' => true,
    ],
    //8
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
                'value' => 'atencion-pqrs'
            ],
            [
                'key' => 'record.route',
                'value' => 'filament.admin.resources.pqrs.create'
            ],
        ],
        'compliance' => true,
    ],
    //9
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
                'value' => 'auditoria-interna'
            ],
            [
                'key' => 'schedule.route',
                'value' => 'cronograma-de-auditoria-interna-ivc'
            ]
        ],
        'compliance' => true,
    ],
    //10
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
                'value' => 'planes-mejora'
            ],
            [
                'key' => 'record.route',
                'value' => 'filament.admin.resources.plan-de-mejora.index'
            ]
        ],
        'compliance' => true,
    ],
    //11
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
                'value' => 'evaluacion-gestion-riesgos'
            ],
            [
                'key' => 'matriz.riesgos', 
                'value' => 'risk.matrix.pdf'
            ],
        ],
        'compliance' => true,
    ],
    //12
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
                'value' => 'filament.admin.resources.indicadores-de-gestion.index'
            ]
        ],
        'compliance' => true,
    ],
];



