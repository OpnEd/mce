<?php


return [
    [
        'apply' => true,
        'entry_id' => '16.1',
        'criticality' => 'Mayor',
        'question' => 'Se cuenta con procedimiento para el manejo de medicamentos de cadena de frío. Incluye la recepción, almacenamiento y dispensación de los mismos.?',
        'answer' => '',
        'entry_type' => 'text',
        'links' => [
            'document.manejo-de-medicamentos-de-cadena-de-frio' => 'document.details',
            'record.matriculas' => 'filament.admin.resources.quality.training.enrollments.index'
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '16.2',
        'criticality' => 'Crítico',
        'question' => 'Los medicamentos que requieren refrigeración se almacenan en refrigeradores o congeladores que garanticen que la temperatura se mantiene en el rango establecido por el fabricante y registrado en cajas y etiquetas.?',
        'answer' => '',
        'entry_type' => 'text',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '16.3',
        'criticality' => 'Crítico',
        'question' => 'Se cuenta con termómetro calibrado que indica la temperatura de almacenamiento de los medicamentos en los refrigeradores o congeladores. Se lleva registro de la temperatura de almacenamiento en los refrigeradores o congeladores.?',
        'answer' => '',
        'entry_type' => 'text',
        'links' => [
            'folder.cadena-de-frio' => 'etiqueta 16.3',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '16.4',
        'criticality' => 'Crítico',
        'question' => 'Se cuenta con un plan de emergencia (contingencia) que garantiza el mantenimiento de la cadena de frío, en caso de interrupciones de la energía eléctrica o daño de los refrigeradores o congeladores. Se cuenta con los elementos apropiados.',
        'answer' => '',
        'entry_type' => 'text',
        'links' => [
            'document.plan-de-emergencia-cadena-de-frio' => 'document.details',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '16.5',
        'criticality' => 'Crítico',
        'question' => 'Se cuenta con los elementos apropiados para la dispensación de medicamentos que requieren cadena de frio.?',
        'answer' => '',
        'entry_type' => 'text',
        'links' => null,
        'compliance' => true,
    ],
];