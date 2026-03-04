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
        'entry_id' => '9.1',
        'criticality' => 'menor',
        'question' => '¿El establecimiento cuenta con un plan de capacitación de por lo menos 10 horas anuales, continuo y permanente para el personal manipulador de alimentos?',
        'answer' => 'Desarrollamos nuestros procesos de tal forma que los indicadores reflejan el cumplimiento con las expectativas de la comunidad.',
        'entry_type' => EntryType::FOLDER,
        'links' => [
            [
                'key' => 'etiqueta',
                'value' => '9.1'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.2',
        'criticality' => 'menor',
        'question' => '¿Se evidencia desarrollo e implementación de actividades que Impulsan estilos de vida saludables y el uso adecuado de los medicamentos y dispositivos médicos?',
        'answer' => 'Como puede verse, tenemos material didáctico al alcance de nuestros usuarios',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.3',
        'criticality' => 'menor',
        'question' => ' ¿Se evidencia desarrollo e implementación de actividades que previenen la ocurrencia de factores de riesgo derivados del uso inadecuado de medicamentos y dispositivos médicos, así como problemas relacionados con su uso?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'record.route',
                'value' => 'filament.admin.resources.promocion-uso-racional-medicamentos.index'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.4',
        'criticality' => 'menor',
        'question' => '¿Cuenta con anuncio claro y destacado al interior del local, referente a "Prohibida la venta de alcohol industrial y antiséptico a niños, niñas y adolescentes? ¿Ingerirlo puede generar graves efectos sobre la salud, incluso la muerte"?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.5',
        'criticality' => 'Mayor',
        'question' => '¿Cuenta con inscripción ante la Secretaría Distrital de Salud a través de la página de autorregulación?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.6',
        'criticality' => 'Mayor',
        'question' => '¿El personal dispensador recibe la capacitación ofrecida por las entidades oficiales o de otros actores
del Sector Salud y/o se capacita continuamente en los conocimientos teóricos y destrezas necesarias en el
ejercicio del cargo u oficio, a fin de ir aumentando progresivamente las competencias laborales?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'page.route',
                'value' => 'filament.admin.resources.quality.training.enrollments.index'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.7',
        'criticality' => 'menor',
        'question' => '¿Cuenta con anuncio claro al interior del local referente a los espacios libres de humo?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ]
];



