<?php


return [
    [
        'apply' => true,
        'entry_id' => '11.1',
        'criticality' => 'Mayor',
        'question' => 'Cuenta con procedimiento que permita adquirir los medicamentos y dispositivos médicos, que incluya programación de necesidades, decisión de adquisición y prevalencia del conocimiento técnico, con el fin de tenerlos disponibles para la satisfacción de la demanda y necesidad de sus usuarios, beneficiarios o destinatarios.?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'document.adquisicion-de-medicamentos-y-dispositivos-medicos' => 'document.details',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '11.2',
        'criticality' => 'Crítico',
        'question' => 'Los proveedores están autorizados por la autoridad sanitaria para comercializar, fabricar o importar productos. Se cuenta con copia de la autorización, con fecha de visita inferior a un año, cuyo concepto no sea desfavorable',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'upload.acta-ivc-proveedor-principal' => 'por.definir',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '11.3',
        'criticality' => 'Mayor',
        'question' => 'Cuenta con criterios que permitan continuamente controlar y evaluar el proceso de adquisición de medicamentos y dispositivos médicos',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '11.4',
        'criticality' => 'Mayor',
        'question' => 'El personal conoce el procedimiento y el alcance de sus funciones y responsabilidades.',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'record.socializacion-de-adquisicion' => 'filament.admin.resources.quality.training.enrollments.index',
        ],
        'compliance' => true,
    ],
];
