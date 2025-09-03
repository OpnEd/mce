<?php


return [

[
        'apply' => true,
        'entry_id' => '14.1',
        'criticality' => 'Crítico',
        'question' => 'Se cuenta con un procedimiento de dispensación de medicamentos, dispositivos médicos y demás productos autorizados. Se diferencia la dispensación de productos de venta con formula médica de la de venta libre',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'document.dispensacion-de-medicamentos-y-dispositivos-medicos' => 'document.details',
        ],
        'compliance' => true,
    ],
[
        'apply' => true,
        'entry_id' => '14.2',
        'criticality' => 'Crítico',
        'question' => 'El establecimiento presta el servicio de dispensación a domicilio. Se incluye este servicio en el procedimiento de dispensación. Se lleva registro de verificación de formulas medicas, previo al envio de los medicamentos. Se cumple con las condiciones de recurso humano y transporte de este servicio?',
        'answer' => '',
        'entry_type' => 'boolean',
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
        'entry_type' => 'boolean',
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
        'entry_type' => 'boolean',
        'links' => null,
        'compliance' => true,
    ],
[
        'apply' => true,
        'entry_id' => '14.5',
        'criticality' => 'Crítico',
        'question' => 'En el acto de entrega física de los medicamentos, el dispensador informa al usuario sobre los aspectos indispensables que promuevan el uso adecuado de los medicamentos y previenen su uso irracional (condiciones de almacenamiento en casa, cómo reconstituirlos, medición de dosis, reporte de efectos adversos, y la importancia de la adherencia a la terapia. Se llevan registros?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'record.promocion-del-uso-adecuado-de-medicamentos' => 'por.definir',
            'document.dispensacion-de-medicamentos-y-dispositivos-medicos' => 'document.details',
        ],
        'compliance' => true,
    ],
[
        'apply' => true,
        'entry_id' => '14.6',
        'criticality' => 'Crítico',
        'question' => 'El establecimiento farmacéutico registra en los medios existentes para tal fin, preferiblemente computarizados, la cantidad, fecha, etc., de los medicamentos y dispositivos médicos dispensados?',
        'answer' => '',
        'entry_type' => 'boolean',
        'links' => [
            'record.movimientos-de-inventario' => 'filament.pos.pages.dashboard',
        ],
        'compliance' => true,
    ],
[
        'apply' => true,
        'entry_id' => '14.7',
        'criticality' => 'Crítico',
        'question' => 'Se cuenta con sistema de registro para la recepción de fórmulas médicas, emisión de órdenes de elaboración y entrega de las preparaciones magistrales. Estas preparaciones cumplen con las condiciones de rotulado?',
        'answer' => '',
        'entry_type' => 'text',
        'links' => null,
        'compliance' => true,
    ],
[
        'apply' => true,
        'entry_id' => '14.8',
        'criticality' => 'Mayor',
        'question' => 'Cuenta con criterios que permitan continuamente controlar y evaluar el proceso de dispensación de medicamentos y dispositivos médicos?',
        'answer' => '',
        'entry_type' => 'boolean',
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
        'entry_type' => 'boolean',
        'links' => [
            'record.socializacion-de-dispensacion' => 'filament.admin.resources.quality.training.enrollments.index',
        ],
        'compliance' => true,
    ],
];