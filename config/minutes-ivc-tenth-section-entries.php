<?php

use App\Models\MinutesIvcSectionEntry as EntryType;

return [
    [
        'apply' => true,
        'entry_id' => '10.2',
        'criticality' => 'Mayor',
        'question' => 'Se cuenta con criterios que permitan continuamente controlar y evaluar el proceso de selección de medicamentos y dispositivos médicos?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '10.3',
        'criticality' => 'Mayor',
        'question' => 'El personal conoce el procedimiento y el alcance de sus funciones y esponsabilidades?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => [
            'record.socializacion-de-seleccion' => 'filament.admin.resources.quality.training.enrollments.index',
        ],
        'compliance' => true,
    ],
];


