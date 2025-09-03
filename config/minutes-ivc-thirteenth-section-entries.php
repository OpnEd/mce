<?php










return [
    [
        'apply' => true,
        'entry_id' => '13.1',
        'criticality' => 'Mayor',
        'question' => 'Se cuenta con un procedimiento de almacenamiento de medicamentos, dispositivos médicos y demás productos autorizados, el cual incluye el ordenamiento de acuerdo al tipo o categoría de los productos que se van a distribuir y/o dispensar?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'document.almacenamiento-de-medicamentos-y-dispositivos-medicos' => 'document.details',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '13.2',
        'criticality' => 'Mayor',
        'question' => 'Los medios de almacenamiento (espacios, áreas físicas, estanterías, muebles, vitrinas) tienen códigos asignados o se cuenta con otro sistema de ordenamiento?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '13.3',
        'criticality' => 'Crítico',
        'question' => 'Los dispositivos médicos y los medicamentos se almacenan de acuerdo con la clasificación farmacológica (medicamentos) en orden alfabético o cualquier otro método de clasificación, siempre y cuando se garantice el orden, se minimicen los eventos de confusión, pérdida y vencimiento durante su almacenamiento. Se tiene cuidado de los medicamentos LASA (suenan igual - parecen iguales) y MAR (medicamentos de alto riesgo)?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '13.4',
        'criticality' => 'Mayor',
        'question' => 'Existen registros permanentes de las condiciones de temperatura y humedad relativa de las diferentes áreas de almacenamiento. Los registros se encuentran dentro de especificaciones?',
        'answer' => '',
        'entry_type' => 'boolean',
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
        'entry_type' => 'boolean',
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
        'entry_type' => 'boolean',
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
        'entry_type' => 'boolean',
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
        'entry_type' => 'boolean',
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
        'entry_type' => 'boolean',
        'links' => [
            'record.socializacion-de-almacenamiento' => 'filament.admin.resources.quality.training.enrollments.index',
        ],
        'compliance' => true,
    ],
];