<?php

return [

  /*
    |--------------------------------------------------------------------------
    | Plantillas de Documentos por Defecto
    |--------------------------------------------------------------------------
    |
    | Cada array define un documento que se creará para el tenant tras
    | registrarse. Usamos los códigos de ProcessType y DocumentType
    | para enlazar correctamente.
    |
    */
  'default_docs' => [
    //Inducción y Capacitación del Personal
    [
      'title'         => 'Inducción y Capacitación del Personal',
      'process_id'  => 'A-RH',   // Proceso de Apoyo
      'document_category_id' => 'PR',  // Código de Procedimiento
      // Secciones textuales
      'objective'             => 'Establecer el proceso sistemático de inducción y capacitación del personal
      de la droguería, de modo que adquieran conocimientos, habilidades y actitudes
      para desempeñar sus funciones conforme a los requisitos del Sistema de Gestión
      de la Calidad (ISO 9001:2015).',

      'scope'                 => 'Aplica a todos los cargos y niveles de la droguería, desde el personal de
      mostrador hasta la dirección, cubriendo tanto la inducción inicial como
      las capacitaciones periódicas.',

      // Arrays de ítems
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'ISO 9001:2015, cláusulas 7.2 (Competencia) y 7.3 (Conciencia).',
          'Resolución 1403 de 2007, Modelo de Gestión del Servicio Farmacéutico.',
        ]
      ),

      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Inducción: Proceso de presentación de la organización, su política de calidad y funciones generales.',
          'Capacitación: Actividad formativa dirigida a actualizar conocimientos técnicos y normativos.',
          'Formador: Persona designada para impartir sesiones de formación.',
        ]
      ),

      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Gerente de Calidad: Aprobar planes de capacitación y evaluar eficacia.',
          'Jefe de Recursos Humanos: Coordinar fechas, instructores y logística.',
          'Líder de Proceso: Identificar necesidades de formación en su área.',
          'Empleados: Asistir puntualmente y participar activamente.',
        ]
      ),

      'procedure' => array_map(
        fn(array $step) => [
          'activity'    => $step['activity'],
          'description'  => $step['description'],
          'responsible'  => $step['responsible'],
          'records'    => $step['records'],
        ],
        [
          [
            'activity'    => '7.1a Planificación de la capacitación',
            'description'  => 'El Líder de Proceso envía anualmente a RR.HH. un listado de necesidades formativas (técnicas y normativas).',
            'responsible'  => 'Líder de Proceso',
            'records'    => 'Listado de necesidades formativas',
          ],
          [
            'activity'    => '7.1b Planificación de la capacitación',
            'description'  => 'RR.HH. consolida el Plan Anual de Capacitación y lo valida con la Gerencia de Calidad.',
            'responsible'  => 'Recursos Humanos',
            'records'    => 'Plan Anual de Capacitación validado',
          ],
          [
            'activity'    => '7.2a Programa de inducción',
            'description'  => 'Presentación de la historia, misión, estructura y procedimientos críticos.',
            'responsible'  => 'Recursos Humanos',
            'records'    => 'Acta de Inducción',
          ],
          [
            'activity'    => '7.2b Programa de inducción',
            'description'  => 'Firma de Acta de Inducción por parte del nuevo colaborador.',
            'responsible'  => 'Colaborador ingresante',
            'records'    => 'Acta firmada',
          ],
          [
            'activity'    => '7.3a Ejecución de capacitaciones',
            'description'  => 'Envío de citación con temario y fecha.',
            'responsible'  => 'Recursos Humanos',
            'records'    => 'Citación enviada',
          ],
          [
            'activity'    => '7.3b Ejecución de capacitaciones',
            'description'  => 'Impartición de la sesión presencial o virtual utilizando material didáctico.',
            'responsible'  => 'Formador designado',
            'records'    => 'Lista de asistencia',
          ],
          [
            'activity'    => '7.4a Evaluación de la eficacia',
            'description'  => 'Realización de prueba de conocimientos al finalizar la sesión.',
            'responsible'  => 'Formador',
            'records'    => 'Resultados de la prueba',
          ],
          [
            'activity'    => '7.4b Evaluación de la eficacia',
            'description'  => 'Consolidación y entrega del informe de eficacia a Gerencia de Calidad.',
            'responsible'  => 'Formador',
            'records'    => 'Informe de eficacia',
          ],
          [
            'activity'    => '7.5a Seguimiento y mejora',
            'description'  => 'Análisis trimestral de los resultados de evaluación.',
            'responsible'  => 'Gerente de Calidad',
            'records'    => 'Acta de revisión de eficacia',
          ],
          [
            'activity'    => '7.5b Seguimiento y mejora',
            'description'  => 'Programación de acciones correctivas si la eficacia es menor al 80%.',
            'responsible'  => 'Gerente de Calidad',
            'records'    => 'Registro de acciones correctivas',
          ],
        ]
      ),

      // Slug y registros
      'slug'                  => 'procedimiento-induccion-capacitacion',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'acta_induccion',
          'asistencia',
          'evaluaciones',
          'informe_eficacia',
        ]
      ),

      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Plantilla de plan de capacitación anual',
          'Guía de instructores y materiales de formación',
        ]
      ),

      'data'                  => [
        'version'   => '1.0',
        'vigencia'  => '01-01-2025'
      ], // si necesitas datos extra, p. ej. responsables o fechas
      'prepared_by'              => '',
      'reviewed_by'              => '',
      'approved_by'              => '',
    ],
    //Procedimiento de Limpieza de Derrames
    [
      'title'         => 'Procedimiento de Limpieza de Derrames',
      'process_id'    => 'A-SM',   // Proceso de Apoyo - Saneamiento y mantenimiento
      'document_category_id' => 'PR',  // Código de Procedimiento

      // Secciones textuales
      'objective'     => 'Establecer las acciones y responsabilidades para responder de manera segura a derrames de medicamentos o sustancias químicas en la droguería, minimizando riesgos para el personal y el ambiente, y garantizando el cumplimiento de la normatividad sanitaria.',

      'scope'         => 'Aplica a todo el personal de la droguería (regente, auxiliares, técnicos, servicios generales) y cubre cualquier área del establecimiento (bodega, mostrador, almacenamiento, laboratorio). Incluye la detección, contención, limpieza, disposición final de residuos y registro del incidente.',

      // Arrays de ítems
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolución 1403 de 2007, Modelo de Gestión del Servicio Farmacéutico.',
          'Decreto 2200 de 2005 y Decreto 2330 de 2006.',
          'Decreto 1011 de 2006, Sistema Obligatorio de Garantía de la Calidad.',
          'Resolución 2003 de 2014, normas de habilitación de servicios de salud.',
          'Protocolos internos de bioseguridad y manejo de residuos peligrosos.',
        ]
      ),

      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Residuo peligroso: Desecho que presenta características de riesgo para la salud o el ambiente (tóxico, corrosivo, inflamable).',
          'Kit de derrames: Conjunto de materiales (absorbentes, guantes, mascarilla, bolsas rojas, pala, escobilla, hipoclorito) destinados al control de derrames.',
          'Bioseguridad: Conjunto de medidas preventivas para proteger la salud del personal ante agentes químicos o biológicos.',
        ]
      ),

      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Regente de Farmacia: Supervisar la correcta aplicación del procedimiento y registrar el incidente.',
          'Auxiliares/Técnicos: Ejecutar las acciones de limpieza usando el kit de derrames y el EPP adecuado.',
          'Servicios Generales: Apoyar el aseo general posterior a la contención.',
          'Todos los empleados: Reportar de inmediato cualquier derrame que observen.',
        ]
      ),

      'procedure' => array_map(
        fn(array $step) => [
          'activity'    => $step['activity'],
          'description' => $step['description'],
          'responsible' => $step['responsible'],
          'records'     => $step['records'],
        ],
        [
          [
            'activity'    => '7.1 Detección y alerta',
            'description' => 'El personal que detecte el derrame debe informar de inmediato al Regente de Farmacia, señalizar el área y evitar el ingreso de otras personas.',
            'responsible' => 'Cualquier empleado',
            'records'     => 'Notificación verbal y registro de incidente',
          ],
          [
            'activity'    => '7.2 Colocación de EPP',
            'description' => 'El personal encargado debe colocarse guantes, mascarilla, bata y gafas antes de manipular el derrame.',
            'responsible' => 'Auxiliar/Técnico asignado',
            'records'     => 'Registro de uso de EPP',
          ],
          [
            'activity'    => '7.3 Contención inicial',
            'description' => 'Colocar material absorbente alrededor del derrame para evitar su propagación. En caso de polvos, cubrir con toallas secas.',
            'responsible' => 'Auxiliar/Técnico asignado',
            'records'     => 'Registro de contención',
          ],
          [
            'activity'    => '7.4 Limpieza',
            'description' => 'Absorber el líquido con material absorbente, recogerlo con pala/escobilla y depositarlo en bolsa roja. Desinfectar con solución de hipoclorito.',
            'responsible' => 'Auxiliar/Técnico asignado',
            'records'     => 'Registro de limpieza y disposición',
          ],
          [
            'activity'    => '7.5 Lavado y desinfección',
            'description' => 'Lavar el área con agua y jabón o hipoclorito diluido desde la zona menos contaminada hacia la más contaminada. Lavar y guardar los implementos.',
            'responsible' => 'Auxiliar/Técnico asignado',
            'records'     => 'Registro de limpieza del área',
          ],
          [
            'activity'    => '7.6 Atención al personal',
            'description' => 'Si hubo exposición, retirar la ropa contaminada y lavar la piel afectada con abundante agua y jabón. Reportar a seguridad laboral.',
            'responsible' => 'Empleado afectado y supervisor',
            'records'     => 'Registro de incidente ocupacional',
          ],
          [
            'activity'    => '7.7 Reporte y registro',
            'description' => 'Completar el formato de registro de limpieza de derrames con fecha, sustancia, cantidad, causa, medidas aplicadas y responsable.',
            'responsible' => 'Regente de Farmacia',
            'records'     => 'Formato de limpieza de derrames diligenciado',
          ],
        ]
      ),

      // Slug y registros
      'slug' => 'procedimiento-limpieza-derrames',

      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'formato_limpieza_derrames',
          'registro_incidentes',
        ]
      ),

      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Formato de Registro de Limpieza de Derrames',
          'Lista de verificación del Kit de Derrames',
        ]
      ),

      'data' => [
        'version'  => '1.0',
        'vigencia' => '15-09-2025'
      ],

      'prepared_by' => '',
      'reviewed_by' => '',
      'approved_by' => '',
    ],
    //Procedimiento de Gestion Documental
    [
      'title'         => 'Procedimiento de Gestion Documental',
      'process_id'    => 'A-GC',
      'document_category_id' => 'PR',
      'objective'     => 'Establecer los controles para elaborar, revisar, aprobar, publicar, actualizar y retirar documentos del Sistema de Gestion de la Calidad, evitando el uso de versiones obsoletas o no aprobadas.',
      'scope'         => 'Aplica a todos los documentos internos y externos que soportan los procesos del establecimiento farmaceutico: procedimientos, formatos, instructivos, guias, listados maestros y anexos.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolucion 1403 de 2007 - Manual de Condiciones Esenciales y Procedimientos del Servicio Farmaceutico.',
          'Decreto 780 de 2016 - Sistema General de Seguridad Social en Salud.',
          'ISO 9001:2015, numeral 7.5 Informacion documentada.',
          'Politica y lineamientos internos de calidad del establecimiento.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Documento controlado: documento sujeto a version, aprobacion y trazabilidad.',
          'Listado maestro: inventario oficial de documentos vigentes y su version.',
          'Documento obsoleto: documento retirado de uso por actualizacion o anulacion.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Direccion tecnica: aprobar documentos criticos y sus actualizaciones.',
          'Lideres de proceso: elaborar y actualizar documentos de su proceso.',
          'Responsable de calidad: custodiar listado maestro y verificar vigencia documental.',
          'Todo el personal: usar solo documentos vigentes disponibles en los medios autorizados.',
        ]
      ),
      'procedure' => array_map(
        fn(array $step) => [
          'activity' => $step['activity'],
          'description' => $step['description'],
          'responsible' => $step['responsible'],
          'records' => $step['records'],
        ],
        [
          [
            'activity' => '7.1 Elaboracion del documento',
            'description' => 'El lider de proceso redacta el documento con codigo, version, fecha y estructura institucional.',
            'responsible' => 'Lider de proceso',
            'records' => 'Borrador del documento',
          ],
          [
            'activity' => '7.2 Revision tecnica',
            'description' => 'Se valida pertinencia normativa, claridad operativa y coherencia con otros documentos del sistema.',
            'responsible' => 'Responsable de calidad',
            'records' => 'Formato de revision documental',
          ],
          [
            'activity' => '7.3 Aprobacion',
            'description' => 'La direccion tecnica aprueba formalmente y autoriza su emision.',
            'responsible' => 'Direccion tecnica',
            'records' => 'Acta o registro de aprobacion',
          ],
          [
            'activity' => '7.4 Publicacion y control de version',
            'description' => 'Se actualiza el listado maestro y se publica la version vigente en el medio definido.',
            'responsible' => 'Responsable de calidad',
            'records' => 'Listado maestro actualizado',
          ],
          [
            'activity' => '7.5 Cambios y actualizaciones',
            'description' => 'Las modificaciones se gestionan mediante solicitud de cambio y nueva version aprobada.',
            'responsible' => 'Lider de proceso y responsable de calidad',
            'records' => 'Solicitud de cambio documental',
          ],
          [
            'activity' => '7.6 Retiro de obsoletos',
            'description' => 'Se retiran copias fisicas y digitales obsoletas y se dejan trazadas como anuladas.',
            'responsible' => 'Responsable de calidad',
            'records' => 'Registro de retiro de obsoletos',
          ],
          [
            'activity' => '7.7 Verificacion de cumplimiento',
            'description' => 'Se audita periodicamente el uso de documentos vigentes en los puestos de trabajo.',
            'responsible' => 'Equipo auditor interno',
            'records' => 'Informe de verificacion documental',
          ],
        ]
      ),
      'slug' => 'procedimiento-de-gestion-documental',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'listado_maestro_documentos',
          'solicitud_cambio_documental',
          'control_documentos_obsoletos',
          'acta_aprobacion_documental',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Formato de listado maestro de documentos',
          'Formato de solicitud de cambios documentales',
          'Formato de control de documentos obsoletos',
        ]
      ),
      'data' => [
        'version' => '1.0',
        'vigencia' => '04-03-2026',
      ],
      'prepared_by' => '',
      'reviewed_by' => '',
      'approved_by' => '',
    ],
    //Procedimiento de Limpieza de Areas
    [
      'title'         => 'Procedimiento de Limpieza de Areas',
      'process_id'    => 'A-SM',
      'document_category_id' => 'PR',
      'objective'     => 'Definir actividades estandarizadas para limpieza y desinfeccion de areas, muebles y superficies del establecimiento, garantizando condiciones higienico-sanitarias seguras.',
      'scope'         => 'Aplica a banos, estanterias, vitrinas, paredes, pisos, techos, bodega, area de atencion y demas espacios del establecimiento farmaceutico.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Ley 9 de 1979 - medidas sanitarias.',
          'Resolucion 1403 de 2007 - condiciones esenciales del servicio farmaceutico.',
          'Resolucion 591 de 2024 - gestion integral de residuos en salud.',
          'Protocolos internos de limpieza y bioseguridad.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Limpieza: remocion de suciedad visible de superficies y equipos.',
          'Desinfeccion: proceso para reducir carga microbiologica con agente quimico autorizado.',
          'Frecuencia: periodicidad establecida para cada tarea en el cronograma.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Direccion tecnica: aprobar cronograma y recursos de limpieza.',
          'Responsable de saneamiento: coordinar ejecucion y verificacion de actividades.',
          'Personal operativo: ejecutar actividades segun instructivo y registrar evidencias.',
          'Responsable de calidad: verificar cumplimiento y cierre de hallazgos.',
        ]
      ),
      'procedure' => array_map(
        fn(array $step) => [
          'activity' => $step['activity'],
          'description' => $step['description'],
          'responsible' => $step['responsible'],
          'records' => $step['records'],
        ],
        [
          [
            'activity' => '7.1 Programacion',
            'description' => 'Se define cronograma por area, frecuencia, responsable e insumos requeridos.',
            'responsible' => 'Responsable de saneamiento',
            'records' => 'Cronograma de limpieza',
          ],
          [
            'activity' => '7.2 Preparacion',
            'description' => 'Se prepara EPP, implementos y desinfectantes autorizados en concentraciones definidas.',
            'responsible' => 'Personal operativo',
            'records' => 'Checklist de insumos y EPP',
          ],
          [
            'activity' => '7.3 Limpieza de superficies',
            'description' => 'Se realiza limpieza de arriba hacia abajo y de zonas limpias a zonas sucias para evitar contaminacion cruzada.',
            'responsible' => 'Personal operativo',
            'records' => 'Registro diario de limpieza',
          ],
          [
            'activity' => '7.4 Desinfeccion',
            'description' => 'Se aplica desinfectante con tiempo de contacto recomendado por el fabricante.',
            'responsible' => 'Personal operativo',
            'records' => 'Registro de desinfeccion',
          ],
          [
            'activity' => '7.5 Disposicion de residuos',
            'description' => 'Se clasifican y disponen residuos generados conforme al plan de gestion de residuos.',
            'responsible' => 'Personal operativo',
            'records' => 'Registro de disposicion de residuos',
          ],
          [
            'activity' => '7.6 Verificacion',
            'description' => 'Se inspecciona el cumplimiento del cronograma y se gestionan acciones correctivas ante incumplimientos.',
            'responsible' => 'Responsable de saneamiento y calidad',
            'records' => 'Formato de verificacion y acciones',
          ],
        ]
      ),
      'slug' => 'procedimiento-de-limpieza',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'cronograma_limpieza_areas',
          'registro_diario_limpieza',
          'registro_desinfeccion_areas',
          'verificacion_limpieza',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Formato de cronograma de limpieza y desinfeccion',
          'Lista de chequeo de limpieza por area',
          'Instructivo de preparacion de soluciones desinfectantes',
        ]
      ),
      'data' => [
        'version' => '1.0',
        'vigencia' => '04-03-2026',
      ],
      'prepared_by' => '',
      'reviewed_by' => '',
      'approved_by' => '',
    ],
    //Procedimiento de Medicion de Satisfaccion del Usuario
    [
      'title'         => 'Procedimiento de Medicion de Satisfaccion del Usuario',
      'process_id'    => 'E-RS',
      'document_category_id' => 'PR',
      'objective'     => 'Establecer la metodologia para medir, analizar y mejorar la satisfaccion de los usuarios del establecimiento farmaceutico.',
      'scope'         => 'Aplica a usuarios atendidos en ventanilla, canales virtuales y actividades de seguimiento del servicio.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolucion 1403 de 2007 - Sistema de gestion del servicio farmaceutico.',
          'Decreto 780 de 2016 - atencion centrada en el usuario.',
          'ISO 9001:2015, numeral 9.1.2 Satisfaccion del cliente.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Satisfaccion del usuario: percepcion del usuario frente al cumplimiento de sus expectativas.',
          'Encuesta de satisfaccion: instrumento estructurado para evaluar experiencia de servicio.',
          'Plan de mejora: acciones para cerrar brechas detectadas en la medicion.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Direccion tecnica: aprobar instrumento, metas y plan de mejora.',
          'Responsable de calidad: consolidar y analizar resultados.',
          'Personal de dispensacion: aplicar encuesta y promover participacion.',
          'Lideres de proceso: ejecutar acciones de mejora derivadas de resultados.',
        ]
      ),
      'procedure' => array_map(
        fn(array $step) => [
          'activity' => $step['activity'],
          'description' => $step['description'],
          'responsible' => $step['responsible'],
          'records' => $step['records'],
        ],
        [
          [
            'activity' => '7.1 Diseno del instrumento',
            'description' => 'Se define encuesta con variables de oportunidad, trato, informacion, disponibilidad y satisfaccion global.',
            'responsible' => 'Responsable de calidad',
            'records' => 'Formato de encuesta aprobado',
          ],
          [
            'activity' => '7.2 Plan de aplicacion',
            'description' => 'Se establece periodicidad, muestra, canal de aplicacion y metas de participacion.',
            'responsible' => 'Responsable de calidad',
            'records' => 'Plan de medicion',
          ],
          [
            'activity' => '7.3 Recoleccion de informacion',
            'description' => 'Se aplican encuestas y se custodian datos garantizando confidencialidad.',
            'responsible' => 'Personal de dispensacion',
            'records' => 'Encuestas diligenciadas',
          ],
          [
            'activity' => '7.4 Analisis de resultados',
            'description' => 'Se calculan indicadores, tendencias y causas de insatisfaccion por periodo.',
            'responsible' => 'Responsable de calidad',
            'records' => 'Informe de resultados',
          ],
          [
            'activity' => '7.5 Acciones de mejora',
            'description' => 'Se formulan acciones correctivas y preventivas con responsables y fechas de cierre.',
            'responsible' => 'Lideres de proceso',
            'records' => 'Plan de mejora por satisfaccion',
          ],
          [
            'activity' => '7.6 Seguimiento',
            'description' => 'Se verifica eficacia de las acciones implementadas y se reporta en revision por la direccion.',
            'responsible' => 'Direccion tecnica y calidad',
            'records' => 'Acta de seguimiento',
          ],
        ]
      ),
      'slug' => 'procedimiento-medicion-satisfaccion-usuario',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'encuesta_satisfaccion_usuario',
          'base_resultados_satisfaccion',
          'informe_satisfaccion_usuario',
          'plan_mejora_satisfaccion_usuario',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Formato de encuesta de satisfaccion',
          'Matriz de analisis de resultados',
          'Formato de plan de mejora por experiencia de usuario',
        ]
      ),
      'data' => [
        'version' => '1.0',
        'vigencia' => '04-03-2026',
      ],
      'prepared_by' => '',
      'reviewed_by' => '',
      'approved_by' => '',
    ],
    //Procedimiento de Medicion de Satisfaccion del Usuario
    [
      'title'         => 'Procedimiento de Gestion de Quejas',
      'process_id'    => 'E-RS',
      'document_category_id' => 'PR',
      'objective'     => 'Definir el proceso para recepcion, clasificacion, evaluacion, respuesta y cierre de quejas de usuarios, con trazabilidad y enfoque de mejora.',
      'scope'         => 'Aplica a todas las quejas recibidas por canales presenciales, telefonicos, escritos o digitales relacionadas con el servicio farmaceutico.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolucion 1403 de 2007 - servicio farmaceutico.',
          'Decreto 780 de 2016 - calidad y atencion al usuario.',
          'Lineamientos internos de atencion al usuario y gestion de PQRS.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Queja: expresion de inconformidad frente al servicio recibido.',
          'PQRS: peticiones, quejas, reclamos y sugerencias.',
          'Cierre efectivo: respuesta emitida, accion ejecutada y evidencia verificada.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Responsable de atencion al usuario: recibir y registrar quejas.',
          'Direccion tecnica: evaluar casos criticos y aprobar respuesta final cuando aplique.',
          'Lideres de proceso: implementar acciones correctivas derivadas.',
          'Responsable de calidad: verificar tiempos de respuesta y cierre.',
        ]
      ),
      'procedure' => array_map(
        fn(array $step) => [
          'activity' => $step['activity'],
          'description' => $step['description'],
          'responsible' => $step['responsible'],
          'records' => $step['records'],
        ],
        [
          [
            'activity' => '7.1 Recepcion',
            'description' => 'Se recibe la queja por el canal disponible y se confirma recepcion al usuario.',
            'responsible' => 'Responsable de atencion al usuario',
            'records' => 'Formato de recepcion de quejas',
          ],
          [
            'activity' => '7.2 Clasificacion',
            'description' => 'Se clasifica la queja por tipo, severidad y proceso involucrado.',
            'responsible' => 'Responsable de atencion al usuario',
            'records' => 'Matriz de clasificacion de quejas',
          ],
          [
            'activity' => '7.3 Analisis y evaluacion',
            'description' => 'Se investigan causas, evidencias y responsabilidades para definir tratamiento.',
            'responsible' => 'Lider de proceso y direccion tecnica',
            'records' => 'Informe de analisis de causa',
          ],
          [
            'activity' => '7.4 Respuesta al usuario',
            'description' => 'Se emite respuesta clara, oportuna y documentada con acciones adoptadas.',
            'responsible' => 'Direccion tecnica',
            'records' => 'Comunicacion de respuesta',
          ],
          [
            'activity' => '7.5 Accion correctiva',
            'description' => 'Se implementan acciones para eliminar causa y prevenir recurrencia.',
            'responsible' => 'Lider de proceso',
            'records' => 'Plan de accion por queja',
          ],
          [
            'activity' => '7.6 Cierre y seguimiento',
            'description' => 'Se valida cierre efectivo, tiempos de gestion e indicadores de reincidencia.',
            'responsible' => 'Responsable de calidad',
            'records' => 'Registro de cierre de quejas',
          ],
        ]
      ),
      'slug' => 'procedimiento-quejas',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'formato_recepcion_quejas',
          'matriz_control_quejas',
          'respuestas_usuarios',
          'registro_cierre_quejas',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Formato unico de recepcion de quejas',
          'Matriz de trazabilidad y tiempos de respuesta',
          'Plantilla de respuesta al usuario',
        ]
      ),
      'data' => [
        'version' => '1.0',
        'vigencia' => '04-03-2026',
      ],
      'prepared_by' => '',
      'reviewed_by' => '',
      'approved_by' => '',
    ],
    //Procedimiento de Auditoria Interna
    [
      'title'         => 'Procedimiento de Auditoria Interna',
      'process_id'    => 'E-RS',
      'document_category_id' => 'PR',
      'objective'     => 'Establecer la metodologia para planificar, ejecutar, informar y hacer seguimiento a auditorias internas del sistema de gestion del establecimiento farmaceutico.',
      'scope'         => 'Aplica a todos los procesos del establecimiento e incluye auditorias programadas, extraordinarias y seguimiento a hallazgos.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolucion 1403 de 2007 - servicio farmaceutico.',
          'Decreto 780 de 2016 - condiciones de calidad en salud.',
          'ISO 19011:2018 - directrices para auditoria de sistemas de gestion.',
          'ISO 9001:2015, numeral 9.2 Auditoria interna.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Auditoria interna: evaluacion sistematica, independiente y documentada.',
          'Hallazgo: resultado de evaluar evidencia frente a un criterio definido.',
          'No conformidad: incumplimiento de requisito normativo o interno.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Direccion tecnica: aprobar programa anual de auditoria.',
          'Lider de auditoria: planear, ejecutar y emitir informe.',
          'Auditados: facilitar informacion y definir acciones frente a hallazgos.',
          'Responsable de calidad: consolidar seguimiento de planes de accion.',
        ]
      ),
      'procedure' => array_map(
        fn(array $step) => [
          'activity' => $step['activity'],
          'description' => $step['description'],
          'responsible' => $step['responsible'],
          'records' => $step['records'],
        ],
        [
          [
            'activity' => '7.1 Programa anual',
            'description' => 'Se define programa anual con alcance, criterios, procesos a auditar y frecuencia.',
            'responsible' => 'Lider de auditoria',
            'records' => 'Programa anual de auditoria',
          ],
          [
            'activity' => '7.2 Plan de auditoria',
            'description' => 'Se establece plan por auditoria con fecha, equipo auditor, agenda y listas de verificacion.',
            'responsible' => 'Lider de auditoria',
            'records' => 'Plan de auditoria',
          ],
          [
            'activity' => '7.3 Ejecucion',
            'description' => 'Se realiza apertura, revision documental, entrevistas, observacion en campo y cierre.',
            'responsible' => 'Equipo auditor',
            'records' => 'Lista de verificacion diligenciada',
          ],
          [
            'activity' => '7.4 Informe de hallazgos',
            'description' => 'Se emite informe con fortalezas, no conformidades, observaciones y oportunidades de mejora.',
            'responsible' => 'Lider de auditoria',
            'records' => 'Informe de auditoria interna',
          ],
          [
            'activity' => '7.5 Plan de acciones',
            'description' => 'Cada proceso auditado define acciones, responsables y fechas de cumplimiento.',
            'responsible' => 'Lideres de proceso',
            'records' => 'Plan de acciones por hallazgo',
          ],
          [
            'activity' => '7.6 Seguimiento y cierre',
            'description' => 'Se verifica eficacia de acciones y se documenta cierre de hallazgos.',
            'responsible' => 'Responsable de calidad',
            'records' => 'Registro de seguimiento y cierre',
          ],
        ]
      ),
      'slug' => 'procedimiento-auditoria-interna',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'programa_anual_auditoria',
          'plan_auditoria_interna',
          'informe_auditoria_interna',
          'seguimiento_hallazgos_auditoria',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Formato de plan de auditoria interna',
          'Lista de verificacion de auditoria',
          'Formato de informe de auditoria',
        ]
      ),
      'data' => [
        'version' => '1.0',
        'vigencia' => '04-03-2026',
      ],
      'prepared_by' => '',
      'reviewed_by' => '',
      'approved_by' => '',
    ],
    //Procedimiento para Planes de Mejora
    [
      'title'         => 'Procedimiento para Planes de Mejora',
      'process_id'    => 'E-RS',
      'document_category_id' => 'PR',
      'objective'     => 'Definir la metodologia para formular, ejecutar y verificar planes de mejora derivados de auditorias internas, visitas de la autoridad sanitaria, quejas, indicadores y analisis de riesgos.',
      'scope'         => 'Aplica a todos los hallazgos y oportunidades de mejora identificados en procesos asistenciales, administrativos y de soporte del establecimiento.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolucion 1403 de 2007 - servicio farmaceutico.',
          'Decreto 780 de 2016 - gestion de calidad.',
          'ISO 9001:2015, numeral 10 Mejora.',
          'Actas e informes de visita de autoridad sanitaria competente.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Hallazgo: incumplimiento o desviacion detectada frente a requisito.',
          'Accion correctiva: accion para eliminar la causa de una no conformidad.',
          'Plan de mejora: conjunto de acciones con metas, responsables y plazo de cierre.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Direccion tecnica: aprobar y priorizar planes de mejora.',
          'Responsable de calidad: consolidar matriz de hallazgos y seguimiento.',
          'Lideres de proceso: ejecutar acciones y reportar avances.',
          'Equipo directivo: revisar eficacia y definir escalamiento cuando aplique.',
        ]
      ),
      'procedure' => array_map(
        fn(array $step) => [
          'activity' => $step['activity'],
          'description' => $step['description'],
          'responsible' => $step['responsible'],
          'records' => $step['records'],
        ],
        [
          [
            'activity' => '7.1 Recepcion de hallazgos',
            'description' => 'Se registran hallazgos de auditorias, visitas sanitarias, quejas e indicadores en matriz unica.',
            'responsible' => 'Responsable de calidad',
            'records' => 'Matriz de hallazgos',
          ],
          [
            'activity' => '7.2 Priorizacion',
            'description' => 'Se priorizan hallazgos por riesgo sanitario, impacto y urgencia de intervencion.',
            'responsible' => 'Direccion tecnica y calidad',
            'records' => 'Matriz de priorizacion',
          ],
          [
            'activity' => '7.3 Formulacion del plan',
            'description' => 'Se define accion, responsable, recursos, fecha compromiso e indicador de cumplimiento.',
            'responsible' => 'Lider de proceso',
            'records' => 'Plan de mejora aprobado',
          ],
          [
            'activity' => '7.4 Ejecucion',
            'description' => 'Se implementan acciones y se recopilan evidencias objetivas del cumplimiento.',
            'responsible' => 'Responsables asignados',
            'records' => 'Evidencias de ejecucion',
          ],
          [
            'activity' => '7.5 Seguimiento',
            'description' => 'Se revisa avance periodico, cumplimiento de fechas y necesidad de reprogramacion.',
            'responsible' => 'Responsable de calidad',
            'records' => 'Informe de seguimiento',
          ],
          [
            'activity' => '7.6 Verificacion de eficacia y cierre',
            'description' => 'Se verifica que la causa fue controlada y se formaliza cierre del hallazgo.',
            'responsible' => 'Direccion tecnica y calidad',
            'records' => 'Acta de cierre de plan de mejora',
          ],
        ]
      ),
      'slug' => 'procedimiento-planes-de-mejora',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'matriz_hallazgos_y_riesgos',
          'plan_mejora_institucional',
          'seguimiento_planes_mejora',
          'acta_cierre_planes_mejora',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Formato matriz de hallazgos',
          'Formato plan de mejora',
          'Formato de seguimiento y cierre',
        ]
      ),
      'data' => [
        'version' => '1.0',
        'vigencia' => '04-03-2026',
      ],
      'prepared_by' => '',
      'reviewed_by' => '',
      'approved_by' => '',
    ],
    //Procedimiento de Control Integral de Plagas
    [
      'title'         => 'Procedimiento de Control Integral de Plagas',
      'process_id'    => 'A-SM',
      'document_category_id' => 'PR',
      'objective'     => 'Establecer las actividades para prevenir, monitorear y controlar plagas en el establecimiento farmaceutico, garantizando condiciones sanitarias seguras y cumplimiento normativo.',
      'scope'         => 'Aplica a todas las areas del establecimiento: almacenamiento, dispensacion, recepcion, zonas comunes, cuarto de residuos y perimetro externo.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Ley 9 de 1979 - medidas sanitarias.',
          'Resolucion 1403 de 2007 - Manual de Condiciones Esenciales del Servicio Farmaceutico.',
          'Programa interno de saneamiento y bioseguridad.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Plaga: organismo que puede contaminar productos, superficies o ambientes.',
          'Monitoreo: inspeccion periodica para detectar evidencia de presencia de plagas.',
          'Control integrado: combinacion de medidas preventivas, fisicas y quimicas autorizadas.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Direccion tecnica: aprobar el programa y verificar su cumplimiento.',
          'Responsable de saneamiento: ejecutar monitoreo, coordinar intervenciones y registros.',
          'Personal operativo: reportar hallazgos y mantener condiciones de orden y limpieza.',
          'Proveedor de control de plagas: ejecutar tratamiento cuando aplique y emitir soporte tecnico.',
        ]
      ),
      'procedure' => array_map(
        fn(array $step) => [
          'activity' => $step['activity'],
          'description' => $step['description'],
          'responsible' => $step['responsible'],
          'records' => $step['records'],
        ],
        [
          [
            'activity' => '7.1 Diagnostico inicial',
            'description' => 'Se identifican puntos criticos, rutas de ingreso y factores de riesgo por area.',
            'responsible' => 'Responsable de saneamiento',
            'records' => 'Acta de diagnostico inicial',
          ],
          [
            'activity' => '7.2 Plan preventivo',
            'description' => 'Se define plan de medidas preventivas: sellado de accesos, orden, limpieza y manejo de residuos.',
            'responsible' => 'Direccion tecnica y saneamiento',
            'records' => 'Plan preventivo de plagas',
          ],
          [
            'activity' => '7.3 Monitoreo periodico',
            'description' => 'Se realizan inspecciones programadas con lista de chequeo y registro de evidencias.',
            'responsible' => 'Responsable de saneamiento',
            'records' => 'Registro de monitoreo de plagas',
          ],
          [
            'activity' => '7.4 Control correctivo',
            'description' => 'Ante hallazgos se ejecutan acciones correctivas internas o con proveedor autorizado.',
            'responsible' => 'Responsable de saneamiento y proveedor',
            'records' => 'Registro de control correctivo',
          ],
          [
            'activity' => '7.5 Verificacion de eficacia',
            'description' => 'Se valida la efectividad de las acciones y se documenta cierre del evento.',
            'responsible' => 'Direccion tecnica',
            'records' => 'Acta de verificacion de eficacia',
          ],
        ]
      ),
      'slug' => 'procedimiento-control-integral-plagas',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'registro_monitoreo_plagas',
          'registro_control_correctivo_plagas',
          'acta_verificacion_control_plagas',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Formato de inspeccion y monitoreo de plagas',
          'Mapa de puntos criticos y cebaderos',
          'Formato de verificacion de eficacia de tratamiento',
        ]
      ),
      'data' => [
        'version' => '1.0',
        'vigencia' => '04-03-2026',
      ],
      'prepared_by' => '',
      'reviewed_by' => '',
      'approved_by' => '',
    ],
    //Procedimiento Plan de Contingencia para Suministro de Agua Potable
    [
      'title'         => 'Procedimiento Plan de Contingencia para Suministro de Agua Potable',
      'process_id'    => 'A-SM',
      'document_category_id' => 'PR',
      'objective'     => 'Definir las acciones de contingencia para garantizar continuidad operativa y condiciones higienico-sanitarias ante suspension o falla del suministro de agua potable.',
      'scope'         => 'Aplica a todas las actividades del establecimiento que requieren agua potable para limpieza, saneamiento y atencion segura.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Ley 9 de 1979 - condiciones sanitarias.',
          'Resolucion 1403 de 2007 - condiciones esenciales del servicio farmaceutico.',
          'Lineamientos internos de saneamiento y gestion del riesgo.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Contingencia: evento no planificado que afecta la operacion normal.',
          'Agua segura de respaldo: agua apta para uso sanitario almacenada o suministrada temporalmente.',
          'Punto critico operativo: actividad que no puede ejecutarse sin control sanitario del agua.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Direccion tecnica: activar y desactivar el plan de contingencia.',
          'Responsable de saneamiento: coordinar abastecimiento alterno y medidas de control.',
          'Personal operativo: aplicar instrucciones del plan y registrar ejecucion.',
          'Administracion: gestionar proveedor externo o apoyo logistico cuando aplique.',
        ]
      ),
      'procedure' => array_map(
        fn(array $step) => [
          'activity' => $step['activity'],
          'description' => $step['description'],
          'responsible' => $step['responsible'],
          'records' => $step['records'],
        ],
        [
          [
            'activity' => '7.1 Deteccion y alerta',
            'description' => 'Se detecta la interrupcion del servicio y se notifica de inmediato a direccion tecnica.',
            'responsible' => 'Personal operativo',
            'records' => 'Registro de alerta de contingencia',
          ],
          [
            'activity' => '7.2 Activacion del plan',
            'description' => 'Se evalua impacto, se define nivel de contingencia y se comunica al personal.',
            'responsible' => 'Direccion tecnica',
            'records' => 'Acta de activacion del plan',
          ],
          [
            'activity' => '7.3 Abastecimiento alterno',
            'description' => 'Se habilita reserva de agua segura o proveedor temporal para cubrir actividades criticas.',
            'responsible' => 'Responsable de saneamiento',
            'records' => 'Registro de abastecimiento alterno',
          ],
          [
            'activity' => '7.4 Medidas operativas temporales',
            'description' => 'Se ajustan actividades, prioridades y restricciones para mantener seguridad sanitaria.',
            'responsible' => 'Direccion tecnica y personal operativo',
            'records' => 'Bitacora de medidas operativas',
          ],
          [
            'activity' => '7.5 Cierre y verificacion',
            'description' => 'Restablecido el servicio, se verifica normalizacion, limpieza y cierre formal de la contingencia.',
            'responsible' => 'Direccion tecnica',
            'records' => 'Acta de cierre de contingencia',
          ],
        ]
      ),
      'slug' => 'procedimiento-plan-contingencia-suministro-agua-potable',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'registro_alerta_falla_agua',
          'acta_activacion_contingencia_agua',
          'bitacora_contingencia_agua',
          'acta_cierre_contingencia_agua',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Formato de activacion de contingencia por agua',
          'Checklist de medidas sanitarias temporales',
          'Formato de cierre y lecciones aprendidas',
        ]
      ),
      'data' => [
        'version' => '1.0',
        'vigencia' => '04-03-2026',
      ],
      'prepared_by' => '',
      'reviewed_by' => '',
      'approved_by' => '',
    ],
  ],

];
