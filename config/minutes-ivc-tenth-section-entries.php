<?php 

return [
    [
        'apply' => true,
        'entry_id' => '10.1',
        'criticality' => 'Mayor',
        'question' => 'Cuenta con un procedimiento de selección para definir los medicamentos y dispositivos médicos con que se debe contar para asegurar el acceso de los usuarios a ellos? (Que incluya definición de políticas institucionales, establece el mecanismo para determinar los consumos históricos, y aspectos relacionados con las decisiones de selección de medicamentos y dispositivos médicos teniendo en cuenta su seguridad, eficacia, calidad y costo.)',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'document.seleccion-de-medicamentos-y-dispositivos-medicos' => 'document.details',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '10.2',
        'criticality' => 'Mayor',
        'question' => 'Se cuenta con criterios que permitan continuamente controlar y evaluar el proceso de selección de medicamentos y dispositivos médicos?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '10.3',
        'criticality' => 'Mayor',
        'question' => 'El personal conoce el procedimiento y el alcance de sus funciones y esponsabilidades?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'record.socializacion-de-seleccion' => 'filament.admin.resources.quality.training.enrollments.index',
        ],
        'compliance' => true,
    ],
];