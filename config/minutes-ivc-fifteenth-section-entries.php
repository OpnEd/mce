<?php

use App\Models\MinutesIvcSectionEntry as EntryType;

return [
    [
        'apply' => true,
        'entry_id' => '15.1',
        'criticality' => 'Mayor',
        'question' => 'Cuenta con criterios, procedimientos y recursos que permitan verificar continuamente la fecha de vencimiento de los medicamentos y dispositivos médicos.?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => [
            'document.devoluciones' => 'document.details',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '15.2',
        'criticality' => 'Mayor',
        'question' => 'El establecimiento separa inmediatamente del inventario y/o del sitio de almacenamiento los productos retirados del mercado, reportados en alertas sanitarias y próximos a vencer, y los coloca en área de devoluciones para evitar su comercialización.?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '15.4',
        'criticality' => 'Mayor',
        'question' => 'Se lleva registro de las devoluciones de medicamentos, dispositivos médicos y demás productos autorizados, al proveedor.?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => [
            'record.devoluciones' => 'por.definir',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '15.5',
        'criticality' => 'Mayor',
        'question' => 'Cuenta con criterios que permitan continuamente controlar y evaluar el proceso de devolución de medicamentos y dispositivos médicos.?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '15.6',
        'criticality' => 'Mayor',
        'question' => 'El personal conoce el procedimiento y el alcance de sus funciones y responsabilidades.?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => [
            'record.socializacion-de-devoluciones' => 'filament.admin.resources.quality.training.enrollments.index',
        ],
        'compliance' => true,
    ],
];


