<?php

return [

    'default_processes' => [
//Saneamiento y Mantenimiento
        [
            'process_type_id' => 3,
            'records' => [
                'Calibración de equipos',
                'Limpieza y desinfección de tanques de agua',
                'Control de plagas',
            ],
            'code' => 'A-SM',
            'name' => 'Saneamiento y Mantenimiento',
            'description' => 'Garantiza que la infraestructura, equipos y servicios de apoyo de la droguería se mantengan en condiciones higiénicas, seguras y operacionales para preservar la calidad de los medicamentos y la seguridad de usuarios y trabajadores.',
            'suppliers' => [
                'Dirección técnica',
                'Propietario o administración',
                'Proveedores de servicios (mantenimiento, aseo, control de plagas)',
            ],
            'inputs' => [
                'Requerimientos de mantenimiento de infraestructura y equipos',
                'Programas de limpieza y desinfección',
                'Planes de saneamiento ambiental',
                'Reportes de fallas o incidentes en equipos/instalaciones',
            ],
            'procedures' => [
                'Identificación y priorización de necesidades de mantenimiento y saneamiento',
                'Programación y ejecución de limpieza, desinfección y control de plagas',
                'Gestión de mantenimiento preventivo y correctivo de equipos (incluidas neveras y termómetros)',
                'Verificación y registro de la ejecución de actividades de saneamiento y mantenimiento',
            ],
            'outputs' => [
                'Áreas y equipos limpios, desinfectados y en condiciones adecuadas de operación',
                'Registros de limpieza, desinfección, control de plagas y mantenimiento',
                'Disminución de riesgos para la calidad de los medicamentos y la seguridad del personal',
            ],
            'clients' => [
                'Todos los procesos del servicio farmacéutico',
                'Usuarios y comunidad atendida',
            ],
        ],
//Asuntos regulatorios
        [
            'process_type_id' => 1,
            'records' => [],
            'code' => 'A-AR',
            'name' => 'Asuntos Regulatorios',
            'description' => 'Gestiona el cumplimiento de los requisitos legales y normativos aplicables a la droguería y a los procesos del servicio farmacéutico.',
            'suppliers' => [
                'Ministerio de Salud y Protección Social',
                'Secretarías de Salud',
                'INVIMA',
                'Colegio profesional y demás entes de control',
            ],
            'inputs' => [
                'Normatividad legal y sanitaria vigente (leyes, decretos, resoluciones como 2200 de 2005 y 1403 de 2007)',
                'Requerimientos y comunicaciones de autoridades sanitarias',
                'Información sobre cambios normativos',
            ],
            'procedures' => [
                'Revisión y actualización periódica de la normatividad aplicable',
                'Tramitación y renovación de licencias de funcionamiento y habilitación',
                'Atención de visitas de inspección, vigilancia y control',
                'Actualización de procedimientos internos según cambios normativos',
            ],
            'outputs' => [
                'Licencias, registros y certificados vigentes',
                'Respuesta oportuna a requerimientos de entes de control',
                'Documentos legales y normativos actualizados e implementados',
            ],
            'clients' => [
                'Entes regulatorios',
                'Dirección técnica',
                'Procesos misionales y de apoyo',
            ],
        ],
//Planeación y Gerencia
        [
            'process_type_id' => 1,
            'records' => [
                'Planes de mejora',
                'Acciones correctivas o preventivas',
            ],
            'code' => 'D-PG',
            'name' => 'Planeación y Gerencia',
            'description' => 'Define la plataforma estratégica de la droguería (misión, visión, objetivos, política de calidad) y realiza la planeación, seguimiento y evaluación integral de la gestión.',
            'suppliers' => [
                'Dirección técnica',
                'Propietario o gerencia',
                'Todos los procesos del servicio farmacéutico',
            ],
            'inputs' => [
                'Indicadores de gestión y calidad',
                'Resultados de auditorías internas y externas',
                'Información financiera y de ventas',
                'Retroalimentación de usuarios (quejas, sugerencias, encuestas)',
            ],
            'procedures' => [
                'Análisis de contexto, análisis DOFA y análisis de riesgos',
                'Definición y actualización de objetivos, metas e indicadores',
                'Elaboración de planes estratégicos y operativos',
                'Seguimiento periódico a resultados y formulación de acciones de mejora',
            ],
            'outputs' => [
                'Políticas y directrices institucionales',
                'Planes estratégicos, operativos y de mejoramiento',
                'Matriz de riesgos y planes de tratamiento',
            ],
            'clients' => [
                'Todos los procesos del servicio farmacéutico',
                'Propietario/gerencia',
            ],
        ],
//Evaluación, retroalimentación y seguimiento
        [
            'process_type_id' => 4,
            'records' => [
                'Programa de auditoría',
                'Planes de auditorías',
                'Informes de auditorías',
            ],
            'code' => 'E-RS',
            'name' => 'Evaluación Retroalimentación y Seguimiento',
            'description' => 'Recolecta y analiza información sobre el desempeño de los procesos para emitir juicios de cumplimiento y generar oportunidades de mejora.',
            'suppliers' => [
                'Todos los procesos',
                'Usuarios internos y externos',
                'Entes de control',
            ],
            'inputs' => [
                'Indicadores de gestión y calidad',
                'Registros de procesos y productos',
                'Resultados de auditorías, visitas de inspección y PQRS',
            ],
            'procedures' => [
                'Planificación y ejecución de auditorías internas',
                'Entrevistas y revisión de registros como evidencia de ejecución',
                'Análisis de resultados, identificación de no conformidades y oportunidades de mejora',
                'Socialización de hallazgos y seguimiento a planes de acción',
            ],
            'outputs' => [
                'Informes de auditoría y evaluación',
                'Planes de acción correctiva, preventiva y de mejora',
                'Retroalimentación estructurada a los procesos',
            ],
            'clients' => [
                'Todos los procesos',
                'Dirección técnica y gerencia',
            ],
        ],
//Selección
        [
            'process_type_id' => 2,
            'records' => [
                'Listado básico de medicamentos',
                'Selección y evaluación de proveedores',
            ],
            'code' => 'M-SL',
            'name' => 'Selección',
            'description' => 'Define el portafolio de medicamentos y dispositivos médicos a ofrecer, así como los proveedores aprobados, con base en criterios técnicos, normativos y de calidad.',
            'suppliers' => [
                'Usuarios y comunidad atendida',
                'Dirección técnica',
                'Normatividad y listados oficiales (PBS, guías, etc.)',
                'Proveedores de medicamentos y dispositivos médicos',
            ],
            'inputs' => [
                'Información de consumos históricos y patrones de prescripción',
                'Perfil epidemiológico de la población atendida',
                'Información sobre proveedores y condiciones de suministro',
                'Normativa y guías de tratamiento vigentes',
            ],
            'procedures' => [
                'Análisis de consumos y necesidades de la comunidad',
                'Definición y actualización del listado básico de medicamentos y dispositivos médicos',
                'Definición de criterios y evaluación de proveedores',
                'Documentación y aprobación de cambios en el portafolio',
            ],
            'outputs' => [
                'Listado básico de medicamentos y dispositivos médicos actualizado',
                'Relación de proveedores aprobados',
                'Registros de evaluación y selección de proveedores',
            ],
            'clients' => [
                'Proceso de Adquisición',
                'Dirección técnica y gerencia',
            ],
        ],
//Gestión de Recurso Humano
        [
            'process_type_id' => 3,
            'records' => ['Por definir'],
            'code' => 'A-RH',
            'name' => 'Gestión de Recurso Humano',
            'description' => 'Gestiona la vinculación, desarrollo de competencias, bienestar y seguridad del talento humano que participa en los procesos de la droguería.',
            'suppliers' => [
                'Dirección técnica',
                'Área administrativa/contable',
                'Entidades de formación y capacitación',
            ],
            'inputs' => [
                'Normatividad laboral y de seguridad y salud en el trabajo',
                'Hojas de vida y soportes de formación',
                'Evaluaciones de desempeño',
                'Requerimientos de capacitación y perfiles de cargo',
            ],
            'procedures' => [
                'Definición de perfiles y requisitos de cargo',
                'Procesos de selección, vinculación y contratación',
                'Evaluación de desempeño y retroalimentación al personal',
                'Gestión de seguridad y salud en el trabajo (inducción en riesgos, EPP, reportes de incidentes)',
            ],
            'outputs' => [
                'Personal idóneo, vinculado y documentado',
                'Planes y registros de capacitación y desarrollo',
                'Registros de evaluaciones de desempeño',
            ],
            'clients' => [
                'Todos los procesos del servicio farmacéutico',
                'Trabajadores de la droguería',
            ],
        ],
//Inducción y Capacitación
        [
            'process_type_id' => 3,
            'records' => ['Material de inducción y capacitación'],
            'code' => 'A-BL',
            'name' => 'Inducción y Capacitación',
            'description' => 'Desarrolla y conserva el material y los programas de inducción y capacitación para fortalecer las competencias del personal.',
            'suppliers' => [
                'Gestión de Recurso Humano',
                'Dirección técnica',
                'Fuentes científicas y normativas',
            ],
            'inputs' => [
                'Necesidades de formación identificadas en los procesos',
                'Cambios normativos y actualizaciones científicas',
                'Manuales de procesos y procedimientos',
            ],
            'procedures' => [
                'Diseño y actualización de programas de inducción para personal nuevo',
                'Diseño y ejecución de planes de capacitación periódica',
                'Registro de asistencia y evaluación de efectividad de las capacitaciones',
            ],
            'outputs' => [
                'Material didáctico e informativo actualizado',
                'Registros de inducción y capacitación del personal',
                'Mejora en las competencias y desempeño del equipo',
            ],
            'clients' => [
                'Todos los procesos',
                'Personal de la droguería',
            ],
        ],
//Adquisición
        [
            'process_type_id' => 3,
            'records' => ['Orden de compra'],
            'code' => 'M-AQ',
            'name' => 'Adquisición',
            'description' => 'Gestiona la compra de medicamentos y dispositivos médicos, asegurando oportunidad, calidad y cumplimiento de requisitos legales y técnicos.',
            'suppliers' => [
                'Proceso de Selección',
                'Proceso de Dispensación (reporte de faltantes)',
                'Proveedores aprobados',
            ],
            'inputs' => [
                'Listado básico de medicamentos y dispositivos médicos',
                'Solicitudes de reposición e informes de consumo',
                'Cotizaciones y condiciones comerciales de proveedores',
            ],
            'procedures' => [
                'Revisión de niveles de inventario y puntos de reposición',
                'Solicitud y análisis de cotizaciones a proveedores',
                'Emisión y seguimiento de órdenes de compra',
                'Coordinación de condiciones de entrega y transporte',
            ],
            'outputs' => [
                'Órdenes de compra emitidas y documentadas',
                'Productos adquiridos con soportes de facturación',
                'Soportes de proceso contractual y selección de proveedor',
            ],
            'clients' => [
                'Recepción Técnica',
                'Dirección técnica y gerencia',
            ],
        ],
//Recepción Técnica
        [
            'process_type_id' => 3,
            'records' => ['Acta de Recepción técnica y administrativa'],
            'code' => 'M-RT',
            'name' => 'Recepción Técnica',
            'description' => 'Verifica, al ingreso a la droguería, que los medicamentos y dispositivos médicos recibidos cumplan los requisitos administrativos, técnicos y de calidad.',
            'suppliers' => [
                'Proceso de Adquisición',
                'Proveedores de medicamentos y dispositivos médicos',
            ],
            'inputs' => [
                'Medicamentos y dispositivos médicos entregados por el proveedor',
                'Orden de compra y factura',
                'Especificaciones técnicas, políticas de calidad y normatividad vigente',
            ],
            'procedures' => [
                'Recepción administrativa (verificación de factura, cantidades, precios y referencias)',
                'Recepción técnica (estado del empaque, rotulado, registro sanitario, fechas de vencimiento, cadena de frío)',
                'Registro de resultados de la recepción y manejo de no conformidades (rechazos, devoluciones)',
                'Archivo y custodia de documentos de recepción',
            ],
            'outputs' => [
                'Productos recibidos y clasificados como aceptados o rechazados',
                'Registros de recepción administrativa y técnica',
                'Actas de devolución al proveedor cuando aplique',
            ],
            'clients' => [
                'Almacenamiento',
                'Dispensación',
                'Proveedores (en caso de devoluciones)',
            ],
        ],
//Almacenamiento
        [
            'process_type_id' => 3,
            'records' => [
                'Registros de temperatura y humedad',
                'Registros de limpieza y sanitización',
            ],
            'code' => 'M-AT',
            'name' => 'Almacenamiento',
            'description' => 'Conserva los medicamentos y dispositivos médicos en condiciones adecuadas de temperatura, humedad, orden y seguridad hasta su entrega al usuario.',
            'suppliers' => [
                'Recepción Técnica',
                'Saneamiento y Mantenimiento',
            ],
            'inputs' => [
                'Productos aceptados en recepción técnica',
                'Políticas y procedimientos de almacenamiento',
                'Plan de control de fechas de vencimiento y rotación',
            ],
            'procedures' => [
                'Ubicación y organización de productos según forma farmacéutica y riesgo',
                'Monitoreo y registro de condiciones ambientales (temperatura, humedad)',
                'Aplicación de principios PEPS/FEFO y control de fechas de vencimiento',
                'Limpieza y orden sistemático de estanterías y áreas de almacenamiento',
            ],
            'outputs' => [
                'Productos almacenados en condiciones que preservan su calidad',
                'Registros de almacenamiento, control ambiental y vencimientos',
                'Listados de productos próximos a vencer o en mal estado para gestión posterior',
            ],
            'clients' => [
                'Dispensación',
                'Devoluciones y Disposición Final',
            ],
        ],
//Dispensación
        [
            'process_type_id' => 3,
            'records' => [
                'Registros de educación al usuario',
                'Encuestas de satisfacción del usuario',
                'PQRS',
            ],
            'code' => 'M-DP',
            'name' => 'Dispensación',
            'description' => 'Entrega medicamentos y dispositivos médicos al usuario, asegurando el producto correcto, en la cantidad y forma adecuada, con la información necesaria para su uso seguro y efectivo.',
            'suppliers' => [
                'Usuarios y prescriptores',
                'Proceso de Almacenamiento',
            ],
            'inputs' => [
                'Fórmulas médicas y solicitudes de usuarios',
                'Medicamentos y dispositivos almacenados',
                'Procedimientos de dispensación e indicación farmacéutica',
            ],
            'procedures' => [
                'Verificación de la prescripción y de la identidad del usuario',
                'Selección y preparación del medicamento correcto desde almacenamiento',
                'Entrega y explicación de uso, dosis, horarios, duración y advertencias',
                'Registro de dispensaciones y gestión de PQRS relacionadas con el servicio',
            ],
            'outputs' => [
                'Medicamentos y dispositivos dispensados correctamente',
                'Usuarios informados sobre el uso adecuado de sus tratamientos',
                'Registros de dispensación y atención a PQRS',
            ],
            'clients' => [
                'Usuarios y cuidadores',
                'Procesos de Selección y Adquisición (retroalimentación de demanda y faltantes)',
            ],
        ],
//Devoluciones y Disposición Final
        [
            'process_type_id' => 3,
            'records' => [
                'Acta de devoluciones',
                'Acta de disposición final',
            ],
            'code' => 'M-DV',
            'name' => 'Devoluciones y Disposición Final',
            'description' => 'Gestiona las devoluciones de medicamentos desde los usuarios o desde almacenamiento y asegura la disposición final segura de productos deteriorados, vencidos o no aptos para uso.',
            'suppliers' => [
                'Proceso de Almacenamiento',
                'Proceso de Dispensación',
                'Usuarios y proveedores',
            ],
            'inputs' => [
                'Medicamentos devueltos por usuarios o identificados como no aptos',
                'Procedimientos de devolución y disposición final',
                'Requisitos legales y ambientales para destrucción y manejo de residuos',
            ],
            'procedures' => [
                'Recepción, registro y clasificación de medicamentos devueltos',
                'Definición del destino: reintegro, cuarentena, devolución a proveedor o destrucción',
                'Coordinación con gestores autorizados para disposición final de residuos',
                'Elaboración de actas de devolución y actas de disposición final',
            ],
            'outputs' => [
                'Medicamentos devueltos gestionados de forma segura y trazable',
                'Actas y registros de devoluciones y disposición final',
                'Reducción de riesgos sanitarios y ambientales asociados a residuos',
            ],
            'clients' => [
                'Proveedores',
                'Autoridades ambientales y sanitarias',
                'Comunidad y medio ambiente',
            ],
        ],
//Gestión de Recursos
        [
            'process_type_id' => 3,
            'records' => ['Facturas, órdenes de servicio y contratos'],
            'code' => 'A-GR',
            'name' => 'Gestión de Recursos',
            'description' => 'Administra los recursos financieros, físicos y de servicios no farmacéuticos necesarios para el funcionamiento de la droguería.',
            'suppliers' => [
                'Todos los procesos (requerimientos de recursos)',
                'Proveedor de bienes y servicios no farmacéuticos',
            ],
            'inputs' => [
                'Requerimientos de recursos e insumos no farmacéuticos',
                'Presupuesto disponible y proyecciones financieras',
                'Cotizaciones de bienes y servicios de apoyo',
            ],
            'procedures' => [
                'Análisis y priorización de requerimientos de recursos',
                'Elaboración de presupuestos y programación de compras/contrataciones',
                'Gestión de compras de insumos generales, servicios y mantenimiento',
                'Registro y archivo de facturas, contratos y comprobantes',
            ],
            'outputs' => [
                'Recursos y servicios de apoyo disponibles en tiempo y forma',
                'Registros de compras, contratos y pagos',
                'Infraestructura y recursos de apoyo en condiciones adecuadas',
            ],
            'clients' => [
                'Todos los procesos',
                'Propietario/gerencia',
            ],
        ],
//Gestión de Calidad
        [
            'process_type_id' => 3,
            'records' => [],
            'code' => 'A-GC',
            'name' => 'Gestión de Calidad',
            'description' => 'Diseña, documenta, implementa y mejora el sistema de gestión de calidad del servicio farmacéutico de la droguería.',
            'suppliers' => [
                'Dirección técnica',
                'Todos los procesos',
                'Entes de control',
            ],
            'inputs' => [
                'Procesos, procedimientos y productos del servicio farmacéutico',
                'Requisitos normativos y de certificación',
                'Resultados de auditorías, indicadores y PQRS',
            ],
            'procedures' => [
                'Elaboración y actualización de manuales, procesos y procedimientos',
                'Control de documentos y registros del sistema de calidad',
                'Coordinación de actividades de auditoría y mejora continua',
            ],
            'outputs' => [
                'Documentación del sistema de gestión de calidad actualizada y controlada',
                'Registros de implementación y mejora de procesos',
            ],
            'clients' => [
                'Usuarios internos (todo el personal)',
                'Procesos de Selección, Adquisición y demás procesos misionales',
                'Entes de control',
            ],
        ],
//Gestión de Inventarios
        [
            'process_type_id' => 3,
            'records' => [],
            'code' => 'A-GI',
            'name' => 'Gestión de Inventarios',
            'description' => 'Controla las existencias, movimientos y trazabilidad de medicamentos y dispositivos médicos, garantizando información confiable para la toma de decisiones.',
            'suppliers' => [
                'Procesos de Recepción Técnica, Almacenamiento y Dispensación',
                'Usuarios internos',
            ],
            'inputs' => [
                'Registros de entradas, salidas y ajustes de inventario',
                'Información de consumos, pérdidas, vencimientos y devoluciones',
                'Parámetros de stock mínimo, máximo y puntos de reposición',
            ],
            'procedures' => [
                'Registro sistemático de movimientos de inventario (entradas y salidas)',
                'Realización de inventarios físicos y conciliaciones periódicas',
                'Análisis de rotación, vencimientos, pérdidas y diferencias de inventario',
            ],
            'outputs' => [
                'Información actualizada y confiable de existencias y lotes',
                'Reportes de rotación, vencimientos y diferencias de inventario',
                'Alertas para procesos de Selección y Adquisición',
            ],
            'clients' => [
                'Selección y Adquisición',
                'Almacenamiento y Dispensación',
                'Dirección técnica y gerencia',
            ],
        ],

    ],

];