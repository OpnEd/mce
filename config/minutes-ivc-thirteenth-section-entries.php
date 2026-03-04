<?php

use App\Models\MinutesIvcSectionEntry as EntryType;

return [
    [
        'apply' => true,
        'entry_id' => '13.2',
        'criticality' => 'Mayor',
        'question' => 'Los medios de almacenamiento (espacios, áreas físicas, estanterías, muebles, vitrinas) tienen códigos asignados o se cuenta con otro sistema de ordenamiento?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '13.4',
        'criticality' => 'Mayor',
        'question' => 'Existen registros permanentes de las condiciones de temperatura y humedad relativa de las diferentes áreas de almacenamiento. Los registros se encuentran dentro de especificaciones?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => [
            'record.variables-ambientales' => 'filament.admin.resources.variables-ambientales.index',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '13.5',
        'criticality' => 'Mayor',
        'question' => 'Se encuentra calibrado el termohigrómetro. Se cuenta con certificado de calibración del termohigrómetro. Es confiable la indicación que muestra el termohigrómetro?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => [
            'folder.almacenamiento' => 'etiqueta 13.5',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '13.6',
        'criticality' => 'Mayor',
        'question' => 'Los sitios donde se almacenan medicamentos cuentan con mecanismos que garanticen las condiciones de temperatura y humedad relativa recomendadas por el fabricante?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => [
            'record.variables-ambientales' => 'filament.admin.resources.variables-ambientales.index',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '13.7',
        'criticality' => 'menor',
        'question' => 'Cuenta con criterios, procedimientos y recursos que permitan calcular las existencies necesarias para un periodo determinado y que permitan efectuar el control de inventarios?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => [
            'record.pos-stats' => 'filament.pos.pages.dashboard',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '13.8',
        'criticality' => 'Mayor',
        'question' => 'Cuenta con criterios que permitan continuamente controlar y evaluar el proceso de almacenamiento de medicamentos y dispositivos médicos?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => [
            'document.almacenamiento-de-medicamentos-y-dispositivos-medicos' => 'document.details',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '13.9',
        'criticality' => 'Mayor',
        'question' => 'El personal conoce el procedimiento y el alcance de sus funciones y responsabilidades?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => [
            'record.socializacion-de-almacenamiento' => 'filament.admin.resources.quality.training.enrollments.index',
        ],
        'compliance' => true,
    ],
];


