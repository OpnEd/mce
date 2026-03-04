<?php

use App\Models\MinutesIvcSectionEntry as EntryType;

return [

    /*
    |--------------------------------------------------------------------------
    | Minutes IVC Sections
    |--------------------------------------------------------------------------
    */
  
    [
        'apply' => true,
        'entry_id' => '7.1',
        'criticality' => 'menor',
        'question' => 'La selección, que permita definir los medicamentos y dispositivos médicos con que se debe contar para asegurar el acceso de los usuarios a ellos? (Que incluya definición de políticas institucionales, establece el mecanismo para determinar los consumos históricos, y aspectos relacionados con las decisiones de selección de medicamentos y dispositivos médicos teniendo en cuenta su seguridad, eficacia, calidad y costo.)',
        'answer' => 'Sí',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'seleccion-de-medicamentos-y-dispositivos-medicos'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.2',
        'criticality' => 'menor',
        'question' => 'La adquisición de los medicamentos y dispositivos médicos, que incluya programación de necesidades, decisión de adquisición y prevalencia del conocimiento técnico, con el fin de tenerlos disponibles para la satisfacción de la demanda y necesidad de sus usuarios, beneficiarios o destinatarios.?',
        'answer' => 'Sí',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'adquisicion-de-medicamentos-y-dispositivos-medicos'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.3',
        'criticality' => 'menor',
        'question' => 'La Recepción técnica de medicamentos, dispositivos médicos y demás productos autorizados, que incluye la evaluación de documentación de entrega, muestreo e inspección de productos, elaboración del acta de recepción y verificación de las condiciones especiales del transporte que entrega?',
        'answer' => 'Sí',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'recepcion-de-medicamentos-y-dispositivos-medicos'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.4',
        'criticality' => 'menor',
        'question' => 'El almacenamiento de medicamentos, dispositivos médicos y demás productos autorizados, el cual incluye el ordenamiento de acuerdo al tipo o categoría de los productos que se van a distribuir y/o dispensar evitando confusiones?',
        'answer' => 'Sí',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'almacenamiento-de-medicamentos-y-dispositivos-medicos'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.5',
        'criticality' => 'Crítico',
        'question' => 'La dispensación de medicamentos, dispositivos médicos y demás productos autorizados. Se diferencia la dispensación de productos de venta con formula médica de la de venta libre',
        'answer' => 'Sí',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'dispensacion-de-medicamentos-y-dispositivos-medicos'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.6',
        'criticality' => 'menor',
        'question' => 'El manejo de las devoluciones de los productos por concepto de retiros del mercado, reporte en alertas sanitarias y próximos a vencer',
        'answer' => 'Sí',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'devolucion-de-medicamentos-y-dispositivos-medicos'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.7',
        'criticality' => 'menor',
        'question' => 'El reporte de eventos adversos y revisión de alertas sanitarias.',
        'answer' => 'Sí',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'farmacovigilancia'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.8',
        'criticality' => 'menor',
        'question' => 'Los proveedores están autorizados por la autoridad sanitaria para comercializar, fabricar o importar productos. Se cuenta con copia de la autorización, con fecha de visita inferior a un año, cuyo concepto no sea desfavorable',
        'answer' => 'Sí',
        'entry_type' => EntryType::UPLOAD,
        'links' => [
            [
                'key' => 'path',
                'value' => 'public'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.9',
        'criticality' => 'menor',
        'question' => 'Se cuenta con documento de entrega por parte del proveedor, de los medicamentos, dispositivos médicos, productos fitoterapéuticos y demás productos (que contenga lote, fecha vencimiento, registro sanitario, forma farmacéutica, cantidad y nombre, así como el nombre y la dirección del proveedor y destinatario)?',
        'answer' => 'Sí',
        'entry_type' => EntryType::FOLDER,
        'links' => [
            [
                'key' => 'etiqueta',
                'value' => '7.9'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.10',
        'criticality' => 'Crítico',
        'question' => 'Se cuenta con registro o acta de recepción que recoja información de los productos como es la fecha y hora de entrega, cantidad de unidades, número de lote, registro sanitario, fecha de vencimiento, resultado de la recepción?',
        'answer' => '',
        'entry_type' => EntryType::FOLDER,
        'links' => [
            [
                'key' => 'etiqueta',
                'value' => '7.10'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.11',
        'criticality' => 'Mayor',
        'question' => '¿Los productos autorizados se almacenan en estibas o estanterías de material sanitario, impermeable y fácil de limpiar evitando el contacto directo con el piso?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => ''
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.12',
        'criticality' => 'Crítico',
        'question' => 'Los dispositivos médicos y los medicamentos se almacenan de acuerdo con la clasificación farmacológica (medicamentos) en orden alfabético o cualquier otro método de clasificación, siempre y cuando se garantice el orden, se minimicen los eventos de confusión, pérdida y vencimiento durante su almacenamiento. Se tiene cuidado de los medicamentos LASA (suenan igual - parecen iguales) y MAR (medicamentos de alto riesgo)?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'almacenamiento-de-medicamentos-y-dispositivos-medicos'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.13',
        'criticality' => 'menor',
        'question' => '¿El sistema de segregación de los dispositivos médicos y medicamentos garantiza que el lote más próximo a vencerse sea el primero en dispensarse?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'almacenamiento-de-medicamentos-y-dispositivos-medicos'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.14',
        'criticality' => 'Crítico',
        'question' => '¿Los sitios donde se almacenan medicamentos cuentan con condiciones que garanticen la temperatura y humedad relativa recomendadas por el fabricante?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.15',
        'criticality' => 'menor',
        'question' => '¿Los instrumentos de medición de condiciones ambientales se encuentran calibrados?',
        'answer' => '',
        'entry_type' => EntryType::FOLDER,
        'links' => [
            [
                'key' => 'etiqueta',
                'value' => '7.15'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.16',
        'criticality' => 'menor',
        'question' => '¿Existen registros permanentes de las condiciones de temperatura y humedad relativa de las diferentes áreas de almacenamiento? ¿Los registros se encuentran dentro de las condiciones de almacenamiento dadas por el fabricante?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'record.route',
                'value' => 'filament.admin.resources.variables-ambientales.index'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.17',
        'criticality' => 'Crítico',
        'question' => 'Los medicamentos que requieren refrigeración se almacenan en refrigeradores o congeladores que garanticen que la temperatura se mantiene en el rango establecido por el fabricante y registrado en cajas y etiquetas.?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'manejo-productos-refrigerados'
            ],
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.18',
        'criticality' => 'menor',
        'question' => '¿Se lleva registro de las condiciones de refrigeración mediante el uso de instrumentos debidamente calibrados?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'record.route',
                'value' => 'filament.admin.resources.variables-ambientales.index'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.19',
        'criticality' => 'menor',
        'question' => '¿Se cuenta con un plan de emergencia (contingencia) que garantiza el mantenimiento de la cadena de frío, en caso de interrupciones de la energía eléctrica o daño de los refrigeradores o congeladores?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'plan-de-emergencia-cadena-de-frio'
            ],
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.20',
        'criticality' => 'menor',
        'question' => '¿Las áreas de almacenamiento están alejadas de sitios de alta contaminación para conservar adecuadamente los dispositivos médicos y la estabilidad de los medicamentos?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.21',
        'criticality' => 'Mayor',
        'question' => 'En el acto de entrega física de los medicamentos, el dispensador informa al usuario sobre los aspectos indispensables que promuevan el uso adecuado de los medicamentos y previenen su uso irracional (condiciones de almacenamiento en casa, cómo reconstituirlos, medición de dosis, reporte de efectos adversos, y la importancia de la adherencia a la terapia. Se llevan registros?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'dispensacion-de-medicamentos-y-dispositivos-medicos'
            ],
            [
                'key' => 'record.route',
                'value' => 'filament.admin.resources.promocion-uso-racional-medicamentos.index'
            ]
        ],
        'compliance' => true,
    ],
[
        'apply' => true,
        'entry_id' => '7.22',
        'criticality' => 'Mayor',
        'question' => 'El establecimiento farmacéutico registra en los medios existentes para tal fin, la cantidad, fecha, etc., de los medicamentos y dispositivos médicos dispensados?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.23',
        'criticality' => 'menor',
        'question' => 'Participa y conoce de la implementación de los Planes de Gestión de Devolución de Productos Posconsumo de Fármacos o Medicamentos Vencidos.?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'document.slug',
                'value' => 'gestion-de-devoluciones'
            ],
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.24',
        'criticality' => 'menor',
        'question' => 'Se lleva registro de las devoluciones de medicamentos, dispositivos médicos y demás productos autorizados, al proveedor.?',
        'answer' => '',
        'entry_type' => EntryType::ROUTE,
        'links' => [
            [
                'key' => 'record.route',
                'value' => 'por.definir'
            ]
        ],
        'compliance' => true,
    ],
    [
        'apply' => true,
        'entry_id' => '7.25',
        'criticality' => 'Crítico',
        'question' => '¿En el establecimiento solo se desarrollan procesos propios del establecimiento farmacéutico (no está permitida la consulta médica, toma de tensión arterial, la aplicación de procedimientos médicos o estéticos)?',
        'answer' => '',
        'entry_type' => EntryType::TEXT,
        'links' => null,
        'compliance' => true,
    ],
];
