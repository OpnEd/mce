<?php

use App\Models\MinutesIvcSectionEntry as EntryType;

return [
    [
        'apply' => true,
        'entry_id' => '11.3',
        'criticality' => 'Mayor',
        'question' => 'Cuenta con criterios que permitan continuamente controlar y evaluar el proceso de adquisición de medicamentos y dispositivos médicos',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '11.4',
        'criticality' => 'Mayor',
        'question' => 'El personal conoce el procedimiento y el alcance de sus funciones y responsabilidades.',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => [
            'record.socializacion-de-adquisicion' => 'filament.admin.resources.quality.training.enrollments.index',
        ],
        'compliance' => true,
    ],
];



