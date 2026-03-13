<?php

use App\Models\MinutesIvcSectionEntry as EntryType;

return [

    /*
    |--------------------------------------------------------------------------
    | Minutes IVC Sections
    |--------------------------------------------------------------------------
    |
    | 
    | 
    | 
    |
    */
    [
        'apply' => true,
        'entry_id' => '4.1',
        'criticality' => 'Crítico',
        'question' => '¿Cuenta con abastecimiento de agua potable?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '4.2',
        'criticality' => 'menor',
        'question' => '¿Cuenta con plan de contingencia en caso de suspensión del suministro de agua potable?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'plan-contingencia-suministro-agua-potable'
            ],
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '4.3',
        'criticality' => 'Crítico',
        'question' => '¿Para la disposición de residuos líquidos, está conectado a la red de alcantarillado u otro sistema adecuado?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '4.4',
        'criticality' => 'menor',
        'question' => '¿Cuenta con unidad sanitaria en proporción de una por sexo, por cada 15 personas que laboran en el sitio?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '4.5',
        'criticality' => 'Crítico',
        'question' => '¿Se evidencia que se ha desarrollado e implementado un procedimiento para el control integral de plagas?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'control-integral-plagas'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '4.6',
        'criticality' => 'Mayor',
        'question' => '¿Se ha desarrollado e implementado un procedimiento y registros para la limpieza de áreas (baños, estanterías, vitrinas, paredes, pisos, techos, etc)?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'limpieza-sanitizacion-areas'
            ],
            [
                'key' => 'record.route',
                'value' => 'filament.admin.resources.variables-ambientales.index'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '4.7',
        'criticality' => 'Mayor',
        'question' => '¿Cumple con las disposiciones de la Resolución 591 de 2004 según aplique?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '4.8',
        'criticality' => 'menor',
        'question' => '¿Cuenta con extintores con recarga vigente, se encuentran en áreas de libre acceso?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '4.9',
        'criticality' => 'Mayor',
        'question' => '¿Cuenta con botiquín con dotacion vigente?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
];



