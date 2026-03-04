<?php

use App\Models\MinutesIvcSectionEntry as EntryType;

return [
    [
        'apply' => true,
        'entry_id' => '12.4',
        'criticality' => 'Mayor',
        'question' => 'Cuenta con criterios que permitan continuamente controlar y evaluar el proceso de recepción de medicamentos y dispositivos médicos?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '12.5',
        'criticality' => 'Mayor',
        'question' => 'El personal conoce el procedimiento y el alcance de sus funciones y responsabilidades?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => [
            'record.socializacion-de-recepcion' => 'filament.admin.resources.quality.training.enrollments.index',
        ],
        'compliance' => true,
    ],
];


