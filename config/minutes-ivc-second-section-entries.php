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
        'entry_id' => '2.1.1',
        'criticality' => 'menor',
        'question' => 'Nombre del Director Técnico',
        'answer' => '',
        'entry_type' => 'text',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '2.1.2',
        'criticality' => 'menor',
        'question' => 'Tipo de documento de identidad del Director Técnico',
        'answer' => '',
        'entry_type' => 'text',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '2.1.3',
        'criticality' => 'menor',
        'question' => 'Número de documento de identidad del Director Técnico',
        'answer' => '',
        'entry_type' => 'text',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '2.1.4',
        'criticality' => 'Crítico',
        'question' => 'Título o Permiso del D.T.',
        'answer' => '',
        'entry_type' => 'select',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '2.1.5',
        'criticality' => 'menor',
        'question' => 'No. del Título o Registro del D.T.',
        'answer' => '',
        'entry_type' => 'text',
        'links' => [
            [
                'key' => 'folder.recursos-humanos',
                'value' => 'etiqueta 2.1.5'
            ],
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '2.1.4a',
        'criticality' => 'Crítico',
        'question' => 'Título visible al público',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '2.1.6',
        'criticality' => 'Mayor',
        'question' => 'Horario de Trabajo del D.T. (Mín. 8 Hr.)',
        'answer' => '',
        'entry_type' => 'text',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '2.1.7',
        'criticality' => 'Mayor',
        'question' => 'Se cuenta con copia del Contrato de Trabajo del D.T. que incluya funciones específicas',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            [
                'key' => 'document.contrato-de-trabajo-del-d-t',
                'value' => 'document.details'
            ],
            [
                'key' => 'folder.recursos-humanos',
                'value' => 'etiqueta 2.1.7.'
            ],
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '2.2.1',
        'criticality' => 'menor',
        'question' => 'Nombre del Delegado del D.T.',
        'answer' => '',
        'entry_type' => 'text',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '2.2.2',
        'criticality' => 'menor',
        'question' => 'Tipo de Doc. de Ident. Delegado del D.T.',
        'answer' => '',
        'entry_type' => 'select',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '2.2.3',
        'criticality' => 'menor',
        'question' => 'Número Doc. Ident. Delegado',
        'answer' => '',
        'entry_type' => 'text',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '2.2.4',
        'criticality' => 'Mayor',
        'question' => 'Título o Permiso del Delegado',
        'answer' => '',
        'entry_type' => 'select',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '2.2.5',
        'criticality' => 'menor',
        'question' => 'Número del título o permiso del Delegado',
        'answer' => '',
        'entry_type' => 'text',
        'links' => [
            [
                'key' => 'folder.recursos-humanos',
                'value' => 'etiqueta 2.2.5'
            ],
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '2.2.6',
        'criticality' => 'Mayor',
        'question' => 'Horario de trabajo del Delegado (Que cubra la ausencia del DT)',
        'answer' => '',
        'entry_type' => 'text',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '2.2.7',
        'criticality' => 'menor',
        'question' => 'Se evidencia delegación de responsabilidades del DT?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            [
                'key' => 'document.carta-de-delegacion-de-responsabilidades-del-dt',
                'value' => 'document.details'
            ],
            [
                'key' => 'folder.recursos-humanos',
                'value' => 'etiqueta 2.2.7'
            ],
        ],
        'compliance' => true,
    ],
];