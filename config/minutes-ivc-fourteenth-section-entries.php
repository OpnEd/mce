<?php

use App\Models\MinutesIvcSectionEntry as EntryType;

return [
[
        'apply' => true,
        'entry_id' => '14.2',
        'criticality' => 'Crítico',
        'question' => 'El establecimiento presta el servicio de dispensación a domicilio. Se incluye este servicio en el procedimiento de dispensación. Se lleva registro de verificación de formulas medicas, previo al envio de los medicamentos. Se cumple con las condiciones de recurso humano y transporte de este servicio?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => [
            'document.dispensacion-de-medicamentos-y-dispositivos-medicos' => 'document.details',
        ],
        'compliance' => true,
    ],
[
        'apply' => true,
        'entry_id' => '14.3',
        'criticality' => 'Crítico',
        'question' => 'Se tienen establecidos mecanismos que impidan la entrega involuntaria y equivocada de medicamen- tos, dispositivos médicos y demás productos autorizados?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => [
            'document.dispensacion-de-medicamentos-y-dispositivos-medicos' => 'document.details',
        ],
        'compliance' => true,
    ],
[
        'apply' => true,
        'entry_id' => '14.4',
        'criticality' => 'Crítico',
        'question' => 'Se verifica que previo al entrega o despacho, se cumpla con las características de la formula medica?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
[
        'apply' => true,
        'entry_id' => '14.7',
        'criticality' => 'Crítico',
        'question' => 'Se cuenta con sistema de registro para la recepción de fórmulas médicas, emisión de órdenes de elaboración y entrega de las preparaciones magistrales. Estas preparaciones cumplen con las condiciones de rotulado?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
[
        'apply' => true,
        'entry_id' => '14.8',
        'criticality' => 'Mayor',
        'question' => 'Cuenta con criterios que permitan continuamente controlar y evaluar el proceso de dispensación de medicamentos y dispositivos médicos?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => [
            'record.indicadores-de-gestion' => 'filament.admin.pages.management-indictors',
        ],
        'compliance' => true,
    ],
[
        'apply' => true,
        'entry_id' => '14.9',
        'criticality' => 'Crítico',
        'question' => 'El personal conoce el procedimiento y el alcance de sus funciones y responsabilidades?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => [
            'record.socializacion-de-dispensacion' => 'filament.admin.resources.quality.training.enrollments.index',
        ],
        'compliance' => true,
    ],
];


