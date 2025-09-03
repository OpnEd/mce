<?php

return [
    [
        'apply' => true,
        'entry_id' => '12.1',
        'criticality' => 'Mayor',
        'question' => 'Se cuenta con un procedimiento de recepción de medicamentos, dispositivos médicos y demás productos autorizados, que incluye la evaluación de documentación de entrega, muestreo e inspección de productos, elaboración del acta de recepción y verificación de las condiciones especiales del transporte que entrega?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'document.recepcion-de-medicamentos-y-dispositivos-medicos' => 'document.details',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '12.2',
        'criticality' => 'Mayor',
        'question' => 'Se cuenta con documento de entrega por parte del proveedor, de los medicamentos, dispositivos médicos, productos fitoterapéuticos y suplementos dietarios (lote, fecha vencimiento, registro sanitario, cantidad y nombre). Se notifica al proveedor y autoridad competente las inconsistencias?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'folder.recepcion-tecnica' => 'etiqueta 12.2',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '12.3',
        'criticality' => 'Mayor',
        'question' => 'Se cuenta con registro o acta de recepción que recoja información de los productos como es la fecha y hora de entrega, cantidad de unidades, número de lote, registro sanitario, fecha de vencimiento, condiciones de transporte, manipulación, embalaje, material de empaque, condiciones administrativas y tecnicas establecidas en la negociación y la que permita identificar en todo momento la muestra tomada?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'record.lista-de-recepciones-tecnicas' => 'filament.pos.resources.product-receptions.index',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '12.4',
        'criticality' => 'Mayor',
        'question' => 'Cuenta con criterios que permitan continuamente controlar y evaluar el proceso de recepción de medicamentos y dispositivos médicos?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '12.5',
        'criticality' => 'Mayor',
        'question' => 'El personal conoce el procedimiento y el alcance de sus funciones y responsabilidades?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'record.socializacion-de-recepcion' => 'filament.admin.resources.quality.training.enrollments.index',
        ],
        'compliance' => true,
    ],
];