<?php

return [

    'default_processes' => [
        //Saneamiento y Mantenimiento
        [
            'process_type_id' => 3,
            'records' => [
                'Calibracion de equipos',
                'Limpieza y desinfeccion de tanques de agua',
                'Control de plagas',
            ],
            'code' => 'A-SM',
            'name' => 'Saneamiento y Mantenimiento',
            'slug' => 'saneamiento-y-mantenimiento',
            'description' => 'Garantiza que la infraestructura, equipos y servicios de apoyo de la drogueria se mantengan en condiciones higienicas, seguras y operacionales para preservar la calidad de los medicamentos y la seguridad de usuarios y trabajadores.',
            'suppliers' => [
                'Direccion tecnica',
                'Propietario o administracion',
                'Proveedores de servicios (mantenimiento, aseo, control de plagas)',
            ],
            'inputs' => [
                'Requerimientos de mantenimiento de infraestructura y equipos',
                'Programas de limpieza y desinfeccion',
                'Planes de saneamiento ambiental',
                'Reportes de fallas o incidentes en equipos/instalaciones',
            ],
            'procedures' => [
                'Identificacion y priorizacion de necesidades de mantenimiento y saneamiento',
                'Programacion y ejecucion de limpieza, desinfeccion y control de plagas',
                'Gestion de mantenimiento preventivo y correctivo de equipos (incluidas neveras y termometros)',
                'Verificacion y registro de la ejecucion de actividades de saneamiento y mantenimiento',
            ],
            'outputs' => [
                'areas y equipos limpios, desinfectados y en condiciones adecuadas de operacion',
                'Registros de limpieza, desinfeccion, control de plagas y mantenimiento',
                'Disminucion de riesgos para la calidad de los medicamentos y la seguridad del personal',
            ],
            'clients' => [
                'Todos los procesos del servicio farmaceutico',
                'Usuarios y comunidad atendida',
            ],
            'data'        => [
                'version'   => '1.0',
                'vigencia'  => '01-01-2025'
            ],
        ],
        //Asuntos regulatorios
        [
            'process_type_id' => 1,
            'records' => [],
            'code' => 'A-AR',
            'name' => 'Asuntos Regulatorios',
            'slug' => 'asuntos-regulatorios',
            'description' => 'Gestiona el cumplimiento de los requisitos legales y normativos aplicables a la drogueria y a los procesos del servicio farmaceutico.',
            'suppliers' => [
                'Ministerio de Salud y Proteccion Social',
                'Secretarias de Salud',
                'INVIMA',
                'Colegio profesional y demas entes de control',
            ],
            'inputs' => [
                'Normatividad legal y sanitaria vigente (leyes, decretos, resoluciones como 2200 de 2005 y 1403 de 2007)',
                'Requerimientos y comunicaciones de autoridades sanitarias',
                'Informacion sobre cambios normativos',
            ],
            'procedures' => [
                'Revision y actualizacion periodica de la normatividad aplicable',
                'Tramitacion y renovacion de licencias de funcionamiento y habilitacion',
                'Atencion de visitas de inspeccion, vigilancia y control',
                'Actualizacion de procedimientos internos segun cambios normativos',
            ],
            'outputs' => [
                'Licencias, registros y certificados vigentes',
                'Respuesta oportuna a requerimientos de entes de control',
                'Documentos legales y normativos actualizados e implementados',
            ],
            'clients' => [
                'Entes regulatorios',
                'Direccion tecnica',
                'Procesos misionales y de apoyo',
            ],
            'data'        => [
                'version'   => '1.0',
                'vigencia'  => '01-01-2025'
            ],
        ],
        //Planeacion y Gerencia
        [
            'process_type_id' => 1,
            'records' => [
                'Planes de mejora',
                'Acciones correctivas o preventivas',
            ],
            'code' => 'D-PG',
            'name' => 'Planeacion y Gerencia',
            'slug' => 'planeacion-y-gerencia',
            'description' => 'Define la plataforma estrategica de la drogueria (mision, vision, objetivos, politica de calidad) y realiza la planeacion, seguimiento y evaluacion integral de la gestion.',
            'suppliers' => [
                'Direccion tecnica',
                'Propietario o gerencia',
                'Todos los procesos del servicio farmaceutico',
            ],
            'inputs' => [
                'Indicadores de gestion y calidad',
                'Resultados de auditorias internas y externas',
                'Informacion financiera y de ventas',
                'Retroalimentacion de usuarios (quejas, sugerencias, encuestas)',
            ],
            'procedures' => [
                'Analisis de contexto, analisis DOFA y analisis de riesgos',
                'Definicion y actualizacion de objetivos, metas e indicadores',
                'Elaboracion de planes estrategicos y operativos',
                'Seguimiento periodico a resultados y formulacion de acciones de mejora',
            ],
            'outputs' => [
                'Politicas y directrices institucionales',
                'Planes estrategicos, operativos y de mejoramiento',
                'Matriz de riesgos y planes de tratamiento',
            ],
            'clients' => [
                'Todos los procesos del servicio farmaceutico',
                'Propietario/gerencia',
            ],
            'data'        => [
                'version'   => '1.0',
                'vigencia'  => '01-01-2025'
            ],
        ],
        //Evaluacion, retroalimentacion y seguimiento
        [
            'process_type_id' => 4,
            'records' => [
                'Programa de auditoria',
                'Planes de auditorias',
                'Informes de auditorias',
            ],
            'code' => 'E-RS',
            'name' => 'Evaluacion Retroalimentacion y Seguimiento',
            'slug' => 'evaluacion-retroalimentacion-y-seguimiento',
            'description' => 'Recolecta y analiza informacion sobre el desempeno de los procesos para emitir juicios de cumplimiento y generar oportunidades de mejora.',
            'suppliers' => [
                'Todos los procesos',
                'Usuarios internos y externos',
                'Entes de control',
            ],
            'inputs' => [
                'Indicadores de gestion y calidad',
                'Registros de procesos y productos',
                'Resultados de auditorias, visitas de inspeccion y PQRS',
            ],
            'procedures' => [
                'Planificacion y ejecucion de auditorias internas',
                'Entrevistas y revision de registros como evidencia de ejecucion',
                'Analisis de resultados, identificacion de no conformidades y oportunidades de mejora',
                'Socializacion de hallazgos y seguimiento a planes de accion',
            ],
            'outputs' => [
                'Informes de auditoria y evaluacion',
                'Planes de accion correctiva, preventiva y de mejora',
                'Retroalimentacion estructurada a los procesos',
            ],
            'clients' => [
                'Todos los procesos',
                'Direccion tecnica y gerencia',
            ],
            'data'        => [
                'version'   => '1.0',
                'vigencia'  => '01-01-2025'
            ],
        ],
        //Seleccion
        [
            'process_type_id' => 2,
            'records' => [
                'Listado basico de medicamentos',
                'Seleccion y evaluacion de proveedores',
            ],
            'code' => 'M-SL',
            'name' => 'Seleccion',
            'slug' => 'seleccion',
            'description' => 'Define el portafolio de medicamentos y dispositivos medicos a ofrecer, asi como los proveedores aprobados, con base en criterios tecnicos, normativos y de calidad.',
            'suppliers' => [
                'Usuarios y comunidad atendida',
                'Direccion tecnica',
                'Normatividad y listados oficiales (PBS, guias, etc.)',
                'Proveedores de medicamentos y dispositivos medicos',
            ],
            'inputs' => [
                'Informacion de consumos historicos y patrones de prescripcion',
                'Perfil epidemiologico de la poblacion atendida',
                'Informacion sobre proveedores y condiciones de suministro',
                'Normativa y guias de tratamiento vigentes',
            ],
            'procedures' => [
                'Analisis de consumos y necesidades de la comunidad',
                'Definicion y actualizacion del listado basico de medicamentos y dispositivos medicos',
                'Definicion de criterios y evaluacion de proveedores',
                'Documentacion y aprobacion de cambios en el portafolio',
            ],
            'outputs' => [
                'Listado basico de medicamentos y dispositivos medicos actualizado',
                'Relacion de proveedores aprobados',
                'Registros de evaluacion y seleccion de proveedores',
            ],
            'clients' => [
                'Proceso de Adquisicion',
                'Direccion tecnica y gerencia',
            ],
            'data'        => [
                'version'   => '1.0',
                'vigencia'  => '01-01-2025'
            ],
        ],
        //Gestion de Recurso Humano
        [
            'process_type_id' => 3,
            'records' => ['Por definir'],
            'code' => 'A-RH',
            'name' => 'Gestion de Recurso Humano',
            'slug' => 'gestion-de-recurso-humano',
            'description' => 'Gestiona la vinculacion, desarrollo de competencias, bienestar y seguridad del talento humano que participa en los procesos de la drogueria.',
            'suppliers' => [
                'Direccion tecnica',
                'area administrativa/contable',
                'Entidades de formacion y capacitacion',
            ],
            'inputs' => [
                'Normatividad laboral y de seguridad y salud en el trabajo',
                'Hojas de vida y soportes de formacion',
                'Evaluaciones de desempeno',
                'Requerimientos de capacitacion y perfiles de cargo',
            ],
            'procedures' => [
                'Definicion de perfiles y requisitos de cargo',
                'Procesos de seleccion, vinculacion y contratacion',
                'Evaluacion de desempeno y retroalimentacion al personal',
                'Gestion de seguridad y salud en el trabajo (induccion en riesgos, EPP, reportes de incidentes)',
            ],
            'outputs' => [
                'Personal idoneo, vinculado y documentado',
                'Planes y registros de capacitacion y desarrollo',
                'Registros de evaluaciones de desempeno',
            ],
            'clients' => [
                'Todos los procesos del servicio farmaceutico',
                'Trabajadores de la drogueria',
            ],
            'data'        => [
                'version'   => '1.0',
                'vigencia'  => '01-01-2025'
            ],
        ],
        //Induccion y Capacitacion
        [
            'process_type_id' => 3,
            'records' => ['Material de induccion y capacitacion'],
            'code' => 'A-BL',
            'name' => 'Induccion y Capacitacion',
            'slug' => 'induccion-y-capacitacion',
            'description' => 'Desarrolla y conserva el material y los programas de induccion y capacitacion para fortalecer las competencias del personal.',
            'suppliers' => [
                'Gestion de Recurso Humano',
                'Direccion tecnica',
                'Fuentes cientificas y normativas',
            ],
            'inputs' => [
                'Necesidades de formacion identificadas en los procesos',
                'Cambios normativos y actualizaciones cientificas',
                'Manuales de procesos y procedimientos',
            ],
            'procedures' => [
                'Diseno y actualizacion de programas de induccion para personal nuevo',
                'Diseno y ejecucion de planes de capacitacion periodica',
                'Registro de asistencia y evaluacion de efectividad de las capacitaciones',
            ],
            'outputs' => [
                'Material didactico e informativo actualizado',
                'Registros de induccion y capacitacion del personal',
                'Mejora en las competencias y desempeno del equipo',
            ],
            'clients' => [
                'Todos los procesos',
                'Personal de la drogueria',
            ],
            'data'        => [
                'version'   => '1.0',
                'vigencia'  => '01-01-2025'
            ],
        ],
        //Adquisicion
        [
            'process_type_id' => 3,
            'records' => ['Orden de compra'],
            'code' => 'M-AQ',
            'name' => 'Adquisicion',
            'slug' => 'adquisicion',
            'description' => 'Gestiona la compra de medicamentos y dispositivos medicos, asegurando oportunidad, calidad y cumplimiento de requisitos legales y tecnicos.',
            'suppliers' => [
                'Proceso de Seleccion',
                'Proceso de Dispensacion (reporte de faltantes)',
                'Proveedores aprobados',
            ],
            'inputs' => [
                'Listado basico de medicamentos y dispositivos medicos',
                'Solicitudes de reposicion e informes de consumo',
                'Cotizaciones y condiciones comerciales de proveedores',
            ],
            'procedures' => [
                'Revision de niveles de inventario y puntos de reposicion',
                'Solicitud y analisis de cotizaciones a proveedores',
                'Emision y seguimiento de ordenes de compra',
                'Coordinacion de condiciones de entrega y transporte',
            ],
            'outputs' => [
                'ordenes de compra emitidas y documentadas',
                'Productos adquiridos con soportes de facturacion',
                'Soportes de proceso contractual y seleccion de proveedor',
            ],
            'clients' => [
                'Recepcion Tecnica',
                'Direccion tecnica y gerencia',
            ],
            'data'        => [
                'version'   => '1.0',
                'vigencia'  => '01-01-2025'
            ],
        ],
        //Recepcion Tecnica
        [
            'process_type_id' => 3,
            'records' => ['Acta de Recepcion tecnica y administrativa'],
            'code' => 'M-RT',
            'name' => 'Recepcion Tecnica',
            'slug' => 'recepcion-tecnica',
            'description' => 'Verifica, al ingreso a la drogueria, que los medicamentos y dispositivos medicos recibidos cumplan los requisitos administrativos, tecnicos y de calidad.',
            'suppliers' => [
                'Proceso de Adquisicion',
                'Proveedores de medicamentos y dispositivos medicos',
            ],
            'inputs' => [
                'Medicamentos y dispositivos medicos entregados por el proveedor',
                'Orden de compra y factura',
                'Especificaciones tecnicas, politicas de calidad y normatividad vigente',
            ],
            'procedures' => [
                'Recepcion administrativa (verificacion de factura, cantidades, precios y referencias)',
                'Recepcion tecnica (estado del empaque, rotulado, registro sanitario, fechas de vencimiento, cadena de frio)',
                'Registro de resultados de la recepcion y manejo de no conformidades (rechazos, devoluciones)',
                'Archivo y custodia de documentos de recepcion',
            ],
            'outputs' => [
                'Productos recibidos y clasificados como aceptados o rechazados',
                'Registros de recepcion administrativa y tecnica',
                'Actas de devolucion al proveedor cuando aplique',
            ],
            'clients' => [
                'Almacenamiento',
                'Dispensacion',
                'Proveedores (en caso de devoluciones)',
            ],
            'data'        => [
                'version'   => '1.0',
                'vigencia'  => '01-01-2025'
            ],
        ],
        //Almacenamiento
        [
            'process_type_id' => 3,
            'records' => [
                'Registros de temperatura y humedad',
                'Registros de limpieza y sanitizacion',
            ],
            'code' => 'M-AT',
            'name' => 'Almacenamiento',
            'slug' => 'almacenamiento',
            'description' => 'Conserva los medicamentos y dispositivos medicos en condiciones adecuadas de temperatura, humedad, orden y seguridad hasta su entrega al usuario.',
            'suppliers' => [
                'Recepcion Tecnica',
                'Saneamiento y Mantenimiento',
            ],
            'inputs' => [
                'Productos aceptados en recepcion tecnica',
                'Politicas y procedimientos de almacenamiento',
                'Plan de control de fechas de vencimiento y rotacion',
            ],
            'procedures' => [
                'Ubicacion y organizacion de productos segun forma farmaceutica y riesgo',
                'Monitoreo y registro de condiciones ambientales (temperatura, humedad)',
                'Aplicacion de principios PEPS/FEFO y control de fechas de vencimiento',
                'Limpieza y orden sistematico de estanterias y areas de almacenamiento',
            ],
            'outputs' => [
                'Productos almacenados en condiciones que preservan su calidad',
                'Registros de almacenamiento, control ambiental y vencimientos',
                'Listados de productos proximos a vencer o en mal estado para gestion posterior',
            ],
            'clients' => [
                'Dispensacion',
                'Devoluciones y Disposicion Final',
            ],
            'data'        => [
                'version'   => '1.0',
                'vigencia'  => '01-01-2025'
            ],
        ],
        //Dispensacion
        [
            'process_type_id' => 3,
            'records' => [
                'Registros de educacion al usuario',
                'Encuestas de satisfaccion del usuario',
                'PQRS',
            ],
            'code' => 'M-DP',
            'name' => 'Dispensacion',
            'slug' => 'dispensacion',
            'description' => 'Entrega medicamentos y dispositivos medicos al usuario, asegurando el producto correcto, en la cantidad y forma adecuada, con la informacion necesaria para su uso seguro y efectivo.',
            'suppliers' => [
                'Usuarios y prescriptores',
                'Proceso de Almacenamiento',
            ],
            'inputs' => [
                'Formulas medicas y solicitudes de usuarios',
                'Medicamentos y dispositivos almacenados',
                'Procedimientos de dispensacion e indicacion farmaceutica',
            ],
            'procedures' => [
                'Verificacion de la prescripcion y de la identidad del usuario',
                'Seleccion y preparacion del medicamento correcto desde almacenamiento',
                'Entrega y explicacion de uso, dosis, horarios, duracion y advertencias',
                'Registro de dispensaciones y gestion de PQRS relacionadas con el servicio',
            ],
            'outputs' => [
                'Medicamentos y dispositivos dispensados correctamente',
                'Usuarios informados sobre el uso adecuado de sus tratamientos',
                'Registros de dispensacion y atencion a PQRS',
            ],
            'clients' => [
                'Usuarios y cuidadores',
                'Procesos de Seleccion y Adquisicion (retroalimentacion de demanda y faltantes)',
            ],
            'data'        => [
                'version'   => '1.0',
                'vigencia'  => '01-01-2025'
            ],
        ],
        //Devoluciones y Disposicion Final
        [
            'process_type_id' => 3,
            'records' => [
                'Acta de devoluciones',
                'Acta de disposicion final',
            ],
            'code' => 'M-DV',
            'name' => 'Devoluciones y Disposicion Final',
            'slug' => 'devoluciones-y-disposicion-final',
            'description' => 'Gestiona las devoluciones de medicamentos desde los usuarios o desde almacenamiento y asegura la disposicion final segura de productos deteriorados, vencidos o no aptos para uso.',
            'suppliers' => [
                'Proceso de Almacenamiento',
                'Proceso de Dispensacion',
                'Usuarios y proveedores',
            ],
            'inputs' => [
                'Medicamentos devueltos por usuarios o identificados como no aptos',
                'Procedimientos de devolucion y disposicion final',
                'Requisitos legales y ambientales para destruccion y manejo de residuos',
            ],
            'procedures' => [
                'Recepcion, registro y clasificacion de medicamentos devueltos',
                'Definicion del destino: reintegro, cuarentena, devolucion a proveedor o destruccion',
                'Coordinacion con gestores autorizados para disposicion final de residuos',
                'Elaboracion de actas de devolucion y actas de disposicion final',
            ],
            'outputs' => [
                'Medicamentos devueltos gestionados de forma segura y trazable',
                'Actas y registros de devoluciones y disposicion final',
                'Reduccion de riesgos sanitarios y ambientales asociados a residuos',
            ],
            'clients' => [
                'Proveedores',
                'Autoridades ambientales y sanitarias',
                'Comunidad y medio ambiente',
            ],
            'data'        => [
                'version'   => '1.0',
                'vigencia'  => '01-01-2025'
            ],
        ],
        //Gestion de Recursos
        [
            'process_type_id' => 3,
            'records' => ['Facturas, ordenes de servicio y contratos'],
            'code' => 'A-GR',
            'name' => 'Gestion de Recursos',
            'slug' => 'gestion-de-recursos',
            'description' => 'Administra los recursos financieros, fisicos y de servicios no farmaceuticos necesarios para el funcionamiento de la drogueria.',
            'suppliers' => [
                'Todos los procesos (requerimientos de recursos)',
                'Proveedor de bienes y servicios no farmaceuticos',
            ],
            'inputs' => [
                'Requerimientos de recursos e insumos no farmaceuticos',
                'Presupuesto disponible y proyecciones financieras',
                'Cotizaciones de bienes y servicios de apoyo',
            ],
            'procedures' => [
                'Analisis y priorizacion de requerimientos de recursos',
                'Elaboracion de presupuestos y programacion de compras/contrataciones',
                'Gestion de compras de insumos generales, servicios y mantenimiento',
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
            'data'        => [
                'version'   => '1.0',
                'vigencia'  => '01-01-2025'
            ],
        ],
        //Gestion de Calidad
        [
            'process_type_id' => 3,
            'records' => [],
            'code' => 'A-GC',
            'name' => 'Gestion de Calidad',
            'slug' => 'gestion-de-calidad',
            'description' => 'Disena, documenta, implementa y mejora el sistema de gestion de calidad del servicio farmaceutico de la drogueria.',
            'suppliers' => [
                'Direccion tecnica',
                'Todos los procesos',
                'Entes de control',
            ],
            'inputs' => [
                'Procesos, procedimientos y productos del servicio farmaceutico',
                'Requisitos normativos y de certificacion',
                'Resultados de auditorias, indicadores y PQRS',
            ],
            'procedures' => [
                'Elaboracion y actualizacion de manuales, procesos y procedimientos',
                'Control de documentos y registros del sistema de calidad',
                'Coordinacion de actividades de auditoria y mejora continua',
            ],
            'outputs' => [
                'Documentacion del sistema de gestion de calidad actualizada y controlada',
                'Registros de implementacion y mejora de procesos',
            ],
            'clients' => [
                'Usuarios internos (todo el personal)',
                'Procesos de Seleccion, Adquisicion y demas procesos misionales',
                'Entes de control',
            ],
            'data'        => [
                'version'   => '1.0',
                'vigencia'  => '01-01-2025'
            ],
        ],
        //Gestion de Inventarios
        [
            'process_type_id' => 3,
            'records' => [],
            'code' => 'A-GI',
            'name' => 'Gestion de Inventarios',
            'slug' => 'gestion-de-inventarios',
            'description' => 'Controla las existencias, movimientos y trazabilidad de medicamentos y dispositivos medicos, garantizando informacion confiable para la toma de decisiones.',
            'suppliers' => [
                'Procesos de Recepcion Tecnica, Almacenamiento y Dispensacion',
                'Usuarios internos',
            ],
            'inputs' => [
                'Registros de entradas, salidas y ajustes de inventario',
                'Informacion de consumos, perdidas, vencimientos y devoluciones',
                'Parametros de stock minimo, maximo y puntos de reposicion',
            ],
            'procedures' => [
                'Registro sistematico de movimientos de inventario (entradas y salidas)',
                'Realizacion de inventarios fisicos y conciliaciones periodicas',
                'Analisis de rotacion, vencimientos, perdidas y diferencias de inventario',
            ],
            'outputs' => [
                'Informacion actualizada y confiable de existencias y lotes',
                'Reportes de rotacion, vencimientos y diferencias de inventario',
                'Alertas para procesos de Seleccion y Adquisicion',
            ],
            'clients' => [
                'Seleccion y Adquisicion',
                'Almacenamiento y Dispensacion',
                'Direccion tecnica y gerencia',
            ],
            'data'        => [
                'version'   => '1.0',
                'vigencia'  => '01-01-2025'
            ],
        ],

    ],

];
