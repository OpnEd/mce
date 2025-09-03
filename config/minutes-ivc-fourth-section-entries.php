<?php

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
        'entry_type' => 'boolean',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '4.2',
        'criticality' => 'Mayor',
        'question' => '¿Cuenta con plan de contingencia para poder continuar operaciones en casos de suspensión temporal del servicio de suministro de agua potable. En caso de tratarse de tanque de reserva, se le realiza mantenimiento y limpieza con una periodicidad adecuada?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'document.plan-de-contingencia-para-el-suministro-de-agua-potable' => 'document.details',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '4.3',
        'criticality' => 'Crítico',
        'question' => '¿Existe conexión al alcantarillado o a otro sistema adecuado de disposición de residuos líquidos?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '4.4',
        'criticality' => 'Crítico',
        'question' => '¿Cuenta con unidad sanitaria en proporción de una por sexo, por cada 15 personas que laboran en el sitio?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '4.5',
        'criticality' => 'Mayor',
        'question' => '¿Se ha desarrollado e implementado un procedimiento para el control integral de plagas?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'document.control-integral-de-plagas' => 'document.details',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '4.6',
        'criticality' => 'Crítico',
        'question' => '¿Se llevan registros de inspección de instalaciones para constatar que no hay presencia de plagas? En caso de evidenciarse lo contrario, se cuenta con los soportes de contratación del servicio de fumigación? ¿La entidad que presta dicho servicio está autorizada y proporciona las fichas de seguridad de los productos utilizados en el control de plagas?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'record.registro-de-inspeccion-de-instalaciones' => 'filament.admin.resources.variables-ambientales.index',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '4.7',
        'criticality' => 'Mayor',
        'question' => '¿Se ha desarrollado e implementado un procedimiento para la limpieza y sanitización de las áreas (baños, estanterías, vitrinas, paredes, pisos, techos, etc.) y se llevan registros de estas actividades?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'record.registro-de-limpieza-y-sanitizacion-de-areas' => 'por.definir',
            'document.registro-de-limpieza-y-sanitizacion-de-areas' => 'document.details',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '4.8',
        'criticality' => 'Crítico',
        'question' => '¿Cuenta con plan de gestión de residuos que incluye como mínimo los programas básicos: diagnóstico ambiental, separación, movimiento interno, monitoreo, componente externo y plan de contingencia?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'document.plan-de-gestion-de-residuos' => 'document.details',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '4.9',
        'criticality' => 'Mayor',
        'question' => '¿Cuenta con área de manejo de residuos? ¿Cuenta con recipientes y bolsas de acuerdo con el código de colores estandarizado para la separación adecuada de residuos, rotulados con el tipo de residuo que contiene. Se lleva registro de los residuos en el formato RH1 y se presenta anualmente el informe de generación de residuos ante la Secretaría de Salud?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'record.registro-de-residuos-rh1' => 'por.definir',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '4.10',
        'criticality' => 'menor',
        'question' => '¿Cuenta con extintores con recarga vigente?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '4.11',
        'criticality' => 'menor',
        'question' => '¿Cuenta con botiquín con dotacion completa y vigente tipo A, según Resolución Distrital 705 de 2007?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => null,
        'compliance' => true,
    ],
];
