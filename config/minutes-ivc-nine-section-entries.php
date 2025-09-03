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
        'entry_id' => '9.1',
        'criticality' => 'Mayor',
        'question' => 'Se boolean desarrollo e implementación de la Función Administrativa (Planificar, organizar, dirigir coordinar y controlar los servicios relacionados con los medicamentos y dispositivos médicos ofrecidos a los pacientes y a la comunidad en general, con excepción de la prescripción y administración de los medicamentos).',
        'answer' => 'Desarrollamos nuestros procesos de tal forma que los indicadores reflejan el cumplimiento con las expectativas de la comunidad.',
        'entry_type' => 'text',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.2',
        'criticality' => 'Mayor',
        'question' => 'Se boolean desarrollo e implementación de la Función Promoción (Impulsar estilos de vida saludables y el uso adecuado de medicamentos y dispositivos médicos.',
        'answer' => 'Como puede verse, tenemos material didáctico al alcance de nuestros usuarios',
        'entry_type' => 'text',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.3',
        'criticality' => 'Mayor',
        'question' => 'Se boolean desarrollo e implementación de la Función de Prevención (Prevención de factores de riesgo derivados del uso inadecuado de medicamentos y dispositivos médicos, así como problemas relacionados con su uso. Se refiere a la NO venta de medicamentos que tienen la condicion de "Venta con fórmula médica" sin la presentación de la misma, prestación del servicio de inyectología estrictamente con la presentación de la fórmula médica, la no venta de medicamentos alterados, fraudulentos ni reportados en alertas sanitarias del INVIMA, el no recomentar ni inducir al usuario al consumo de medicamentos).',
        'answer' => '',
        'entry_type' => 'text',
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.4',
        'criticality' => 'Mayor',
        'question' => 'Se cuenta con procedimiento para el reporte de eventos adversos.',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'document.farmacovigilancia' => 'document.details',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.5',
        'criticality' => 'Mayor',
        'question' => 'De llegarse a presentar, ¿se informa a la comunidad competente los reportes hechos por la comunidad, de los eventos adversos relacionados con el uso de medicamentos?.',
        'answer' => 'Se cuenta con procedimiento y enlace directo a e-reporting',
        'entry_type' => 'boolean',
        'links' => [
            'document.farmacovigilancia' => 'document.details',
            'record.e-reporting' => 'https://vigiflow-eforms.who-umc.org/co/medicamentos',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.6',
        'criticality' => 'Mayor',
        'question' => 'Se cuenta con organigrama y manual de funciones del personal que labora en el establecimiento.',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'upload.organigrama' => 'por.definir',
            'document.manual-de-funciones' => 'document.details',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.7',
        'criticality' => 'Mayor',
        'question' => 'Se tiene un sistema documental que impida el uso accidental de documentos obsoletos o no aprobados. Los documentos están diseñados, revisados, modificados, autorizados, fechados y distribuidas por las personas autorizadas y se mantienen actualizados.',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'document.manual-de-calidad' => 'document.details',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.8',
        'criticality' => 'Mayor',
        'question' => 'El establecimiento cuenta con una política de calidad documentada. Cuenta con objetivos de calidad que cumplan lo establecido en su politica.',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'document.politica-de-calidad' => 'document.details',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.9',
        'criticality' => 'Mayor',
        'question' => 'El establecimiento ha desarrollado y cuenta con una Misión y una Visión.',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'document.mision' => 'document.details',
            'document.vision' => 'document.details',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.10',
        'criticality' => 'Mayor',
        'question' => 'Los procesos propios del establecimiento farmacéutico se encuentran debidamente caracterizados.',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'document.selección' => 'document.details',
            'document.adquisición' => 'document.details',
            'document.recepción' => 'document.details',
            'document.almacenamiento' => 'document.details',
            'document.dispensación' => 'document.details',
            'document.devolución' => 'document.details',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.11',
        'criticality' => 'Mayor',
        'question' => 'Se muestran los procesos estratégicos y criticos (propios del establecimiento farmacéutico), determinantes de la calidad, su secuencia e interacción (en un mapa de procesos), con base en criterios técnicos previamente definidos.',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'upload.mapa-de-procesos' => 'por.definir',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.12',
        'criticality' => 'Mayor',
        'question' => 'Las políticas y programas de mejoramiento continuo promueven la capacitación del recuro humano? Se cuenta con mecanismo de programación y procedimiento para la inducción y la capacitación del personal?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'document.politica-de-calidad' => 'document.details',
            'record.cronogramas' => 'filament.admin.resources.quality.schedules.index',
            'record.calendario' => 'filament.admin.pages.events',
            'document.procedimiento-induccion-capacitacion' => 'document.details',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.13',
        'criticality' => 'Mayor',
        'question' => 'Se cuenta con registro de capacitación del personal.',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'record.matriculas' => 'filament.admin.resources.quality.training.enrollments.index',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.14',
        'criticality' => 'Mayor',
        'question' => 'Existe un procedimiento documentado para la medición de la satisfacción del usuario? ¿Se cuenta con registros y resultados?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'document.evaluacion-de-la-Satisfaccion-del-usuario' => 'document.details',
            'record.indicador-satisfaccion-del-usuario' => 'filament.admin.pages.management-indicators',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.15',
        'criticality' => 'Mayor',
        'question' => '¿Existe un procedimiento documentado y registros para el control, recepción, clasificación, evaluación y cierre de las quejas presentadas por los usuarios.?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'document.atencion-de-pqrs' => 'document.details',
            'record.formulario-pqrs' => 'por.definir',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.16',
        'criticality' => 'Mayor',
        'question' => '¿Se realiza el seguimiento, análisis y medición de los procesos propios del establecimiento farmacéutico (indicadores de gestión).',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'record.indicadores-de-gestion' => 'filament.admin.pages.management-indicators',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.17',
        'criticality' => 'Mayor',
        'question' => '¿Cuenta con procedimiento y plan de auditoria / autoinspección interna identificando la frecuencia de estas.?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'document.autoinspecciones' => 'document.details',
            'record.schedule-autoinspecciones' => 'filament.admin.pages.events',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.18',
        'criticality' => 'Mayor',
        'question' => 'Se evidencia procedimiento escrito para el desarrollo de planes de mejora, correcciones, acciones correctives, y los resultados de las mismas.?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'document.desarrollo-de-planes-de-mejora' => 'cocument.details',
            'record.planes-de-mejora' => 'por.definir',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.19',
        'criticality' => 'Mayor',
        'question' => 'Se evalúan y se mantienen bajo control los riesgos de mayor severidad de daño y probabilidad de ocurrencia (Matriz de Riesgos).',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'document.evaluacion-y-gestion-de-riesgos' => 'document.details',
            'document.matriz-de-riesgos' => 'document.details',
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '9.20',
        'criticality' => 'Mayor',
        'question' => 'Se presentan periódicamente los resultados de indicadores de Gestión de Calidad del Servicio/Establecimiento Farmacéutico?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'record.indicadores-de-gestion' => 'filament.admin.pages.management-indicators',
        ],
        'compliance' => true,
    ],
];
