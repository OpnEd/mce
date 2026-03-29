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
      'slug'                  => 'induccion-capacitacion',
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
      'document_category_id' => 'MN',
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
      'slug' => 'gestion-documental',
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
            'Ley 9 de 1979 - Código Sanitario Nacional, medidas sanitarias.',
            'Resolución 1403 de 2007 - Determinación de condiciones esenciales del servicio farmacéutico.',
            'Resolución 591 de 2024 - Gestión integral de residuos generados en atención en salud.',
            'Resolución 2003 de 2014 - Procedimientos y condiciones de inscripción de prestadores de servicios de salud.',
            'Cartilla INVIMA - Recomendaciones técnicas de preparación, uso y almacenamiento adecuado del hipoclorito de sodio.',
            'Decreto 780 de 2016 - Único Reglamentario del Sector Salud y Protección Social.',
            'Protocolos internos de limpieza, bioseguridad y control de infecciones.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
            [
                'Limpieza: Remoción mecánica de suciedad visible, materia orgánica y partículas de superficies y equipos mediante agua, detergentes y acción física.',
                'Desinfección: Proceso físico o químico que destruye o inactiva microorganismos patógenos en objetos inanimados, reduciendo la carga microbiológica a niveles seguros mediante agentes químicos autorizados.',
                'Desinfectante de alto nivel: Agente químico capaz de eliminar bacterias vegetativas, bacilo tuberculoso, hongos, virus y algunas esporas. Ej: Peróxido de hidrógeno 7.5%.',
                'Desinfectante de nivel intermedio: Agente químico que inactiva bacterias vegetativas, bacilo tuberculoso, hongos y virus. Ej: Hipoclorito de sodio, alcoholes 70%.',
                'Desinfectante de bajo nivel: Agente químico que elimina bacterias vegetativas, algunos hongos y virus. Ej: Cuaternarios de amonio.',
                'Hipoclorito de sodio: Desinfectante clorado de nivel intermedio, efectivo contra amplio espectro microbiano. Concentración comercial recomendada: 5-5.25%.',
                'ppm (partes por millón): Unidad de concentración equivalente a miligramos por litro (mg/L). 1% = 10,000 ppm.',
                'Área crítica: Zona con alto riesgo de transmisión de infecciones donde se realizan procedimientos invasivos o se manipulan dispositivos estériles.',
                'Área semicrítica: Zona con riesgo moderado de contaminación, con contacto frecuente con pacientes o personal.',
                'Área no crítica o de bajo riesgo: Zona con mínimo contacto con pacientes y baja probabilidad de contaminación.',
                'Frecuencia: Periodicidad establecida para cada tarea de limpieza según clasificación del área en el cronograma.',
                'EPP (Elementos de Protección Personal): Equipamiento para proteger al trabajador de riesgos químicos y biológicos: guantes, gafas, bata/delantal, mascarilla.',
                'Tiempo de contacto: Período mínimo que el desinfectante debe permanecer en contacto con la superficie para lograr eficacia microbicida.',
                'Vida útil de solución preparada: Tiempo máximo de 12 horas para soluciones de hipoclorito diluidas debido a degradación por luz, calor y materia orgánica.',
            ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
            [
                'Director Técnico: Aprobar cronograma de limpieza, asignar recursos humanos y materiales, garantizar disponibilidad de desinfectantes e insumos, validar procedimientos y supervisar cumplimiento normativo.',
                'Químico Farmacéutico Responsable: Capacitar al personal en preparación de soluciones desinfectantes, verificar cálculos de dilución, supervisar rotulación y almacenamiento de productos químicos.',
                'Responsable de Saneamiento Ambiental: Coordinar ejecución del cronograma, verificar cumplimiento de actividades, gestionar inventario de implementos de limpieza, registrar evidencias y reportar incumplimientos.',
                'Personal Operativo de Limpieza: Ejecutar actividades según instructivo, preparar soluciones desinfectantes conforme a protocolos, usar EPP obligatoriamente, registrar actividades diarias y reportar novedades.',
                'Responsable de Gestión de Calidad: Verificar cumplimiento de procedimientos mediante auditorías internas, gestionar acciones correctivas y preventivas, cerrar hallazgos y mantener registros actualizados.',
                'Auxiliar de Farmacia: Colaborar con limpieza de áreas de dispensación y almacenamiento, reportar derrames o contaminación, mantener orden en estanterías y vitrinas.',
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
                    'activity' => '7.1 Programación y planificación',
                    'description' => 'Se elabora cronograma anual de limpieza y desinfección especificando: áreas (críticas, semicríticas, no críticas), frecuencia según clasificación (diaria, semanal, quincenal, mensual), responsable asignado, implementos requeridos (traperos, escobas, paños), desinfectantes a utilizar con concentraciones específicas y horarios de ejecución. El cronograma debe ser aprobado por Dirección Técnica y socializado con todo el personal.',
                    'responsible' => 'Responsable de saneamiento ambiental / Director Técnico',
                    'records' => 'Cronograma anual de limpieza y desinfección de áreas',
                ],
                [
                    'activity' => '7.2 Preparación de insumos y EPP',
                    'description' => 'Se verifica disponibilidad y se preparan elementos de protección personal (guantes de nitrilo, gafas protectoras contra químicos, bata o delantal plástico impermeable, tapabocas), implementos de limpieza según área asignada (traperos, escobas, paños diferenciados por colores), detergentes biodegradables y desinfectantes autorizados. Se realiza inspección previa de estado de implementos y se reportan elementos deteriorados para reemplazo. Nunca manipular desinfectantes sin EPP completo, incluso en exposiciones cortas.',
                    'responsible' => 'Personal operativo de limpieza',
                    'records' => 'Checklist de verificación de insumos y EPP',
                ],
                [
                    'activity' => '7.3 Preparación de soluciones de hipoclorito de sodio',
                    'description' => 'Se preparan soluciones desinfectantes según necesidad específica usando la fórmula: V? = (Cd × Vd) / Cc. Donde: Cd = concentración deseada en ppm, Vd = volumen a preparar en mL, Cc = concentración conocida del producto comercial en ppm, V? = volumen de hipoclorito comercial a utilizar en mL. Usar hipoclorito comercial al 5% (50,000 ppm) o 5.25% (52,500 ppm). Concentraciones según uso: (1) Fluidos biológicos/sangre: 10,000 ppm (200 mL hipoclorito 5% + 800 mL agua), (2) Lavado terminal áreas críticas/semicríticas: 5,000 ppm (100 mL + 900 mL agua), (3) Lavado rutinario áreas críticas/semicríticas: 2,500 ppm (50 mL + 950 mL agua), (4) Áreas no críticas: 2,000 ppm (40 mL + 960 mL agua). Usar ÚNICAMENTE agua desionizada o destilada libre de metales (hierro, cobre, níquel, manganeso) y cloro, pH neutro. Preparar en lugares ventilados. Tiempo de vida útil: máximo 12 horas, desechar inmediatamente después. Rotular envases con: nombre del producto, concentración en ppm, fecha y hora de preparación, nombre de quien preparó, área de uso.',
                    'responsible' => 'Personal operativo capacitado / Químico Farmacéutico supervisa',
                    'records' => 'Registro de preparación de soluciones desinfectantes',
                ],
                [
                    'activity' => '7.4 Limpieza mecánica de superficies',
                    'description' => 'Se realiza limpieza física de arriba hacia abajo (techos → paredes → mobiliario → pisos) y de zonas limpias hacia zonas sucias para evitar contaminación cruzada. Remover polvo, residuos visibles y materia orgánica usando detergente biodegradable y paños húmedos. En estanterías y vitrinas retirar productos temporalmente. Enjuagar con agua limpia abundante. Secar con paño limpio desechable o reutilizable exclusivo del área. La superficie debe quedar libre de residuos antes de aplicar desinfectante.',
                    'responsible' => 'Personal operativo de limpieza',
                    'records' => 'Registro diario de limpieza por área',
                ],
                [
                    'activity' => '7.5 Aplicación de desinfectante',
                    'description' => 'Se aplica solución de hipoclorito de sodio u otro desinfectante autorizado según concentración validada para el tipo de área. Método de aplicación: aspersión uniforme con atomizador o aplicación con paño impregnado. Respetar tiempo de contacto mínimo de 10 minutos para hipoclorito según tabla INVIMA. No mezclar NUNCA hipoclorito con detergentes, ácidos, amoniaco ni agua caliente (genera vapores tóxicos). Evitar contacto con metales (níquel, hierro, acero) por más tiempo del indicado. Ventilar adecuadamente durante y después de aplicación. Enjuagar superficies que tengan contacto directo con medicamentos o alimentos. Desechar solución sobrante conforme a normativa de residuos químicos.',
                    'responsible' => 'Personal operativo de limpieza',
                    'records' => 'Registro de aplicación de desinfectantes',
                ],
                [
                    'activity' => '7.6 Disposición final de residuos',
                    'description' => 'Se clasifican residuos generados durante limpieza según código de colores: ordinarios/no peligrosos (verde), reciclables (gris), peligrosos químicos (rojo). Desinfectantes usados y envases contaminados se disponen como residuos químicos. No reutilizar envases de desinfectantes para otros fines. Seguir protocolos del Plan de Gestión Integral de Residuos (PGIRS). No incinerar envases de hipoclorito. Lavar y desinfectar implementos de limpieza después de cada uso. Almacenar traperos y paños en área exclusiva, secos y ventilados.',
                    'responsible' => 'Personal operativo de limpieza',
                    'records' => 'Registro de gestión de residuos generados en limpieza',
                ],
                [
                    'activity' => '7.7 Almacenamiento de desinfectantes',
                    'description' => 'Hipoclorito comercial concentrado: almacenar en área exclusiva protegida de luz solar y artificial, ventilada, temperatura no superior a 30°C. Usar ÚNICAMENTE envases plásticos opacos con tapa hermética, NUNCA metálicos ni de vidrio. Purgar envases con solución antes de llenar. Señalizar área con advertencias de producto corrosivo. Mantener alejado de materiales combustibles, ácidos y derivados del amonio. Rotación PEPS (primero en entrar, primero en salir). Verificar fecha de vencimiento. Soluciones preparadas: máximo 12 horas de vida útil, protegidas de luz y calor, rotuladas. Mantener hojas de seguridad del producto accesibles.',
                    'responsible' => 'Responsable de saneamiento / Auxiliar de farmacia',
                    'records' => 'Registro de control de inventario de desinfectantes',
                ],
                [
                    'activity' => '7.8 Verificación y auditoría',
                    'description' => 'Se inspecciona cumplimiento del cronograma mediante auditorías programadas y aleatorias. Verificar: ejecución de actividades en horarios establecidos, uso correcto de EPP, concentraciones de desinfectantes preparadas, rotulación de envases, estado de implementos, registros diligenciados, disposición de residuos. Aplicar lista de chequeo por área. Ante hallazgos o incumplimientos, se generan acciones correctivas con responsable y plazo. Realizar seguimiento hasta cierre efectivo. Retroalimentar al personal y registrar lecciones aprendidas.',
                    'responsible' => 'Responsable de saneamiento / Responsable de calidad',
                    'records' => 'Formato de verificación de limpieza / Plan de acciones correctivas',
                ],
                [
                    'activity' => '7.9 Capacitación continua',
                    'description' => 'Se ejecuta programa de capacitación periódica (mínimo semestral) al personal involucrado en: cálculo y preparación de diluciones de hipoclorito, manejo seguro de desinfectantes, uso obligatorio de EPP, técnicas de limpieza de arriba hacia abajo, clasificación de áreas según riesgo, tiempos de contacto, almacenamiento seguro, prevención de intoxicaciones, primeros auxilios ante exposición química. Documentar asistencia y evaluar competencias. Reforzar capacitación ante incidentes o cambios normativos.',
                    'responsible' => 'Director Técnico / Químico Farmacéutico / Responsable de calidad',
                    'records' => 'Registro de capacitaciones / Evaluación de competencias',
                ],
        ]
      ),
      'slug' => 'limpieza-sanitazacion-areas',
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
    //Procedimiento de Atencion de Peticiones, Quejas, Reclamos y Sugerencias
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
      'slug' => 'medicion-satisfaccion-usuario',
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
      'title'         => 'Procedimiento de Atencion de Peticiones, Quejas, Reclamos y Sugerencias',
      'process_id'    => 'E-RS',
      'document_category_id' => 'PR',
      'objective'     => 'Definir el proceso integral para recepcion, clasificacion, analisis, respuesta y cierre de PQRS, garantizando trazabilidad, tiempos de respuesta y enfoque de mejora continua.',
      'scope'         => 'Aplica a todas las peticiones, quejas, reclamos y sugerencias recibidas por canales presenciales, telefonicos, escritos o digitales, relacionadas con el servicio farmaceutico.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolucion 1403 de 2007 - servicio farmaceutico.',
          'Decreto 780 de 2016 - calidad y atencion al usuario.',
          'Ley 1755 de 2015 - derecho de peticion.',
          'Lineamientos internos de atencion al usuario y gestion de PQRS.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Peticion: solicitud de informacion, servicio o tramite.',
          'Queja: expresion de inconformidad frente al servicio recibido.',
          'Reclamo: solicitud de correccion por incumplimiento o no conformidad.',
          'Sugerencia: propuesta de mejora del servicio.',
          'PQRS: peticiones, quejas, reclamos y sugerencias.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Responsable de atencion al usuario: recibir, registrar y dar acuse de recibo.',
          'Direccion tecnica: evaluar casos criticos y aprobar respuestas cuando aplique.',
          'Lideres de proceso: analizar causas y ejecutar acciones correctivas o preventivas.',
          'Responsable de calidad: verificar tiempos de respuesta y cierre efectivo.',
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
            'activity' => '7.1 Recepcion y registro',
            'description' => 'Se recibe la PQRS por el canal disponible, se asigna consecutivo y se confirma recepcion al usuario.',
            'responsible' => 'Responsable de atencion al usuario',
            'records' => 'Formato de recepcion de PQRS',
          ],
          [
            'activity' => '7.2 Acuse y tiempos',
            'description' => 'Se informa al usuario el tiempo estimado de respuesta segun el tipo de solicitud y normatividad vigente.',
            'responsible' => 'Responsable de atencion al usuario',
            'records' => 'Comunicacion de acuse de recibo',
          ],
          [
            'activity' => '7.3 Clasificacion y priorizacion',
            'description' => 'Se clasifica la PQRS por tipo, severidad y proceso involucrado para asignar responsables.',
            'responsible' => 'Responsable de atencion al usuario',
            'records' => 'Matriz de clasificacion de PQRS',
          ],
          [
            'activity' => '7.4 Analisis e investigacion',
            'description' => 'Se revisan evidencias, se identifican causas y se define el tratamiento adecuado.',
            'responsible' => 'Lider de proceso y direccion tecnica',
            'records' => 'Informe de analisis de causa',
          ],
          [
            'activity' => '7.5 Respuesta al usuario',
            'description' => 'Se emite respuesta clara, oportuna y documentada con las acciones adoptadas.',
            'responsible' => 'Direccion tecnica',
            'records' => 'Comunicacion de respuesta',
          ],
          [
            'activity' => '7.6 Accion correctiva o preventiva',
            'description' => 'Se ejecutan acciones para eliminar la causa y prevenir recurrencia cuando aplique.',
            'responsible' => 'Lider de proceso',
            'records' => 'Plan de accion por PQRS',
          ],
          [
            'activity' => '7.7 Cierre y seguimiento',
            'description' => 'Se valida cierre efectivo, se actualizan indicadores y se verifican tiempos de gestion.',
            'responsible' => 'Responsable de calidad',
            'records' => 'Registro de cierre de PQRS',
          ],
          [
            'activity' => '7.8 Analisis de tendencias',
            'description' => 'Se consolidan tendencias y oportunidades de mejora para revision por la direccion.',
            'responsible' => 'Responsable de calidad',
            'records' => 'Informe de tendencias de PQRS',
          ],
        ]
      ),
      'slug' => 'atencion-pqrs',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'formato_recepcion_pqrs',
          'matriz_clasificacion_pqrs',
          'informe_analisis_pqrs',
          'respuesta_pqrs',
          'plan_accion_pqrs',
          'registro_cierre_pqrs',
          'informe_tendencias_pqrs',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Formato unico de recepcion de PQRS',
          'Matriz de clasificacion y tiempos de respuesta',
          'Plantilla de respuesta al usuario',
          'Guia de trato al usuario',
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
      'slug' => 'auditoria-interna',
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
      'slug' => 'planes-mejora',
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
      'title'         => 'Control Integral de Plagas',
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
      'slug' => 'control-integral-plagas',
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
      'title'         => 'Plan de Contingencia para Suministro de Agua Potable',
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
      'slug' => 'plan-contingencia-suministro-agua-potable',
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
    //Planeacion Estrategica
    [
      'title'         => 'Planeacion Estrategica del Establecimiento',
      'process_id'    => 'D-PG',
      'document_category_id' => 'MN',
      'objective'     => 'Definir la plataforma estrategica (mision, vision, politica y objetivos de calidad) y los lineamientos para el direccionamiento de la drogueria.',
      'scope'         => 'Aplica a toda la organizacion y orienta la gestion de todos los procesos del servicio farmaceutico.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'ISO 9001:2015, numerales 4, 5 y 6.',
          'Resolucion 1403 de 2007 - Modelo de Gestion del Servicio Farmaceutico.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Mision: proposito fundamental de la organizacion.',
          'Vision: estado deseado a mediano y largo plazo.',
          'Politica de calidad: lineamiento marco para el sistema de gestion.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Direccion tecnica: liderar la definicion y revision de la plataforma estrategica.',
          'Gerencia/propietario: aprobar objetivos y recursos para su cumplimiento.',
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
            'activity' => '7.1 Analisis de contexto',
            'description' => 'Identificar necesidades del entorno, partes interesadas y riesgos estrategicos.',
            'responsible' => 'Direccion tecnica',
            'records' => 'Analisis de contexto',
          ],
          [
            'activity' => '7.2 Definicion y aprobacion',
            'description' => 'Establecer mision, vision, politica y objetivos con sus metas e indicadores.',
            'responsible' => 'Direccion tecnica y gerencia',
            'records' => 'Plan estrategico aprobado',
          ],
          [
            'activity' => '7.3 Revision periodica',
            'description' => 'Revisar anualmente el cumplimiento y ajustar la plataforma estrategica.',
            'responsible' => 'Direccion tecnica',
            'records' => 'Acta de revision estrategica',
          ],
        ]
      ),
      'slug' => 'planeacion-estrategica',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'plan_estrategico',
          'objetivos_calidad',
          'actas_revision_estrategica',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Matriz DOFA',
          'Mapa de interesados',
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
    //Manual de Funciones del Personal
    [
      'title'         => 'Manual de Funciones del Personal',
      'process_id'    => 'A-RH',
      'document_category_id' => 'MN',
      'objective'     => 'Establecer funciones, responsabilidades y competencias requeridas para cada cargo del establecimiento.',
      'scope'         => 'Aplica a todo el personal que labora en la drogueria.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolucion 1403 de 2007 - Servicio Farmaceutico.',
          'Decreto 2200 de 2005 - Servicios farmaceuticos.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Perfil de cargo: requisitos y competencias para el puesto.',
          'Responsabilidad: deber asignado a un cargo.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Direccion tecnica: aprobar el manual y sus actualizaciones.',
          'Gestion de talento humano: elaborar, socializar y custodiar el manual.',
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
            'activity' => '7.1 Elaboracion y actualizacion',
            'description' => 'Definir perfiles, funciones y competencias por cargo.',
            'responsible' => 'Gestion de talento humano',
            'records' => 'Manual de funciones actualizado',
          ],
          [
            'activity' => '7.2 Socializacion',
            'description' => 'Socializar el manual con el personal y conservar evidencias.',
            'responsible' => 'Gestion de talento humano',
            'records' => 'Acta de socializacion',
          ],
        ]
      ),
      'slug' => 'manual-de-funciones',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'manual_funciones_personal',
          'acta_socializacion_manual',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Perfiles de cargo',
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
    //Manual de Funciones del Director Tecnico
    [
      'title'         => 'Manual de funciones del Director Tecnico',
      'process_id'    => 'A-RH',
      'document_category_id' => 'MN',
      'objective'     => 'Definir las funciones, obligaciones y responsabilidades del Director Tecnico del establecimiento.',
      'scope'         => 'Aplica al Director Tecnico y a su relacion con los demas procesos.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolucion 1403 de 2007 - Servicio Farmaceutico.',
          'Decreto 2200 de 2005 - Director tecnico.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Director tecnico: profesional responsable del servicio farmaceutico.',
          'Delegacion: asignacion temporal de funciones en ausencia.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Director tecnico: cumplir y evidenciar el ejercicio de sus funciones.',
          'Gerencia/propietario: garantizar recursos y condiciones para el cargo.',
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
            'activity' => '7.1 Definicion del cargo',
            'description' => 'Documentar funciones y requisitos del Director Tecnico.',
            'responsible' => 'Direccion tecnica',
            'records' => 'Manual de funciones DT',
          ],
          [
            'activity' => '7.2 Revision y actualizacion',
            'description' => 'Revisar y actualizar el manual cuando cambie la normatividad o el proceso.',
            'responsible' => 'Direccion tecnica',
            'records' => 'Registro de revision del manual',
          ],
        ]
      ),
      'slug' => 'manual-funciones-dt',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'manual_funciones_dt',
          'revision_manual_dt',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Listado de funciones criticas del DT',
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
    //Delegacion de Funciones del Director Tecnico
    [
      'title'         => 'Procedimiento para la delegacion de funciones del Director Tecnico',
      'process_id'    => 'A-RH',
      'document_category_id' => 'PR',
      'objective'     => 'Formalizar la suplencia temporal del Director Tecnico y dejar evidencia del encargo.',
      'scope'         => 'Aplica a ausencias temporales del Director Tecnico.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolucion 1403 de 2007 - Servicio Farmaceutico.',
          'Decreto 2200 de 2005 - Director tecnico.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Delegacion: asignacion temporal de funciones a un responsable.',
          'Suplencia: reemplazo temporal del Director Tecnico.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Director tecnico: diligenciar y firmar la delegacion.',
          'Gerencia/propietario: aprobar y archivar el documento.',
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
            'activity' => '7.1 Diligenciamiento',
            'description' => 'Registrar fechas, responsable designado y alcance de la suplencia.',
            'responsible' => 'Director tecnico',
            'records' => 'Formato de delegacion diligenciado',
          ],
          [
            'activity' => '7.2 Aprobacion y archivo',
            'description' => 'Validar el encargo y conservar el soporte en el archivo de talento humano.',
            'responsible' => 'Gerencia/propietario',
            'records' => 'Registro de delegacion',
          ],
        ]
      ),
      'slug' => 'delegacion-funciones-dt',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'formato_delegacion_funciones_dt',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Formato de delegacion de funciones del DT',
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
    //Mapa de Procesos
    [
      'title'         => 'Mapa de Procesos del Establecimiento',
      'process_id'    => 'A-GC',
      'document_category_id' => 'GF',
      'objective'     => 'Representar graficamente la interaccion entre procesos estrategicos, misionales y de apoyo.',
      'scope'         => 'Aplica a todo el sistema de gestion de la calidad del establecimiento.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'ISO 9001:2015, numeral 4.4.',
          'Lineamientos internos de gestion de calidad.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Proceso: conjunto de actividades que transforma entradas en salidas.',
          'Interaccion: relacion entre procesos para cumplir objetivos.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Responsable de calidad: elaborar y mantener vigente el mapa.',
          'Direccion tecnica: aprobar la version vigente.',
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
            'activity' => '7.1 Diseno del mapa',
            'description' => 'Identificar procesos y definir su secuencia e interacciones.',
            'responsible' => 'Responsable de calidad',
            'records' => 'Mapa de procesos',
          ],
          [
            'activity' => '7.2 Actualizacion',
            'description' => 'Revisar cambios organizacionales y actualizar el diagrama.',
            'responsible' => 'Responsable de calidad',
            'records' => 'Version actualizada del mapa',
          ],
        ]
      ),
      'slug' => 'mapa-de-procesos',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'mapa_de_procesos',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Diagrama del mapa de procesos',
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
    //Evaluacion y Gestion de Riesgos
    [
      'title'         => 'Procedimiento de Evaluacion y Gestion de Riesgos',
      'process_id'    => 'D-PG',
      'document_category_id' => 'PR',
      'objective'     => 'Identificar, valorar y controlar los riesgos que puedan afectar el cumplimiento de objetivos del sistema de gestion.',
      'scope'         => 'Aplica a todos los procesos del establecimiento y a sus riesgos operativos y sanitarios.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'ISO 9001:2015, numeral 6.1.',
          'Lineamientos internos de gestion del riesgo.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Riesgo: efecto de la incertidumbre sobre los objetivos.',
          'Control: accion que reduce la probabilidad o el impacto.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Direccion tecnica: aprobar la metodologia de gestion de riesgos.',
          'Lideres de proceso: identificar, valorar y ejecutar controles.',
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
            'activity' => '7.1 Identificacion',
            'description' => 'Detectar riesgos por proceso y describir sus causas y efectos.',
            'responsible' => 'Lideres de proceso',
            'records' => 'Listado de riesgos',
          ],
          [
            'activity' => '7.2 Valoracion',
            'description' => 'Asignar probabilidad e impacto para priorizar riesgos.',
            'responsible' => 'Responsable de calidad',
            'records' => 'Matriz de riesgos',
          ],
          [
            'activity' => '7.3 Plan de tratamiento',
            'description' => 'Definir controles, responsables y fechas de seguimiento.',
            'responsible' => 'Direccion tecnica y lideres de proceso',
            'records' => 'Plan de tratamiento de riesgos',
          ],
        ]
      ),
      'slug' => 'evaluacion-gestion-riesgos',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'matriz_de_riesgos',
          'plan_tratamiento_riesgos',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Metodologia de valoracion de riesgos',
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
    //Matriz de Riesgos
    [
      'title'         => 'Matriz de Riesgos',
      'process_id'    => 'D-PG',
      'document_category_id' => 'TM',
      'objective'     => 'Consolidar los riesgos identificados y su nivel de priorizacion para el sistema de gestion.',
      'scope'         => 'Aplica a todos los procesos del establecimiento.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'ISO 9001:2015, numeral 6.1.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Probabilidad: posibilidad de ocurrencia del riesgo.',
          'Impacto: consecuencia del riesgo en el proceso.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Responsable de calidad: consolidar y actualizar la matriz.',
          'Lideres de proceso: reportar cambios y acciones.',
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
            'activity' => '7.1 Diligenciamiento',
            'description' => 'Registrar riesgos, controles y nivel de prioridad.',
            'responsible' => 'Lideres de proceso',
            'records' => 'Matriz de riesgos',
          ],
          [
            'activity' => '7.2 Actualizacion',
            'description' => 'Actualizar la matriz cuando haya cambios relevantes.',
            'responsible' => 'Responsable de calidad',
            'records' => 'Matriz de riesgos actualizada',
          ],
        ]
      ),
      'slug' => 'matriz-riesgos',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'matriz_de_riesgos',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Plantilla de matriz de riesgos',
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
    //Procedimiento de Seleccion de Medicamentos y Dispositivos Medicos
    [
      'title'         => 'Procedimiento de Seleccion de Medicamentos y Dispositivos Medicos',
      'process_id'    => 'M-SL',
      'document_category_id' => 'PR',
      'objective'     => 'Establecer criterios y actividades para definir el portafolio de medicamentos y dispositivos medicos.',
      'scope'         => 'Aplica a la seleccion y actualizacion del listado basico de productos.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolucion 1403 de 2007 - Servicio Farmaceutico.',
          'PBS y listados oficiales vigentes.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Listado basico: relacion de productos aprobados.',
          'Criterio tecnico: seguridad, eficacia y calidad.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Director tecnico: liderar la seleccion.',
          'Comite tecnico o responsable designado: evaluar y documentar.',
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
            'activity' => '7.1 Analisis de necesidades',
            'description' => 'Revisar consumos, demandas y prioridades sanitarias.',
            'responsible' => 'Director tecnico',
            'records' => 'Analisis de necesidades',
          ],
          [
            'activity' => '7.2 Evaluacion tecnica',
            'description' => 'Evaluar eficacia, seguridad, calidad y costo.',
            'responsible' => 'Comite tecnico',
            'records' => 'Formato de evaluacion tecnica',
          ],
          [
            'activity' => '7.3 Aprobacion del listado',
            'description' => 'Aprobar y comunicar el listado basico vigente.',
            'responsible' => 'Direccion tecnica',
            'records' => 'Listado basico aprobado',
          ],
        ]
      ),
      'slug' => 'seleccion-de-medicamentos-y-dispositivos-medicos',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'listado_basico_medicamentos',
          'evaluacion_proveedores',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Formato de evaluacion de seleccion',
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
    //Procedimiento de Adquisicion de Medicamentos y Dispositivos Medicos
    [
      'title'         => 'Procedimiento de Adquisicion de Medicamentos y Dispositivos Medicos',
      'process_id'    => 'M-AQ',
      'document_category_id' => 'PR',
      'objective'     => 'Garantizar la adquisicion oportuna y conforme a requisitos tecnicos y legales.',
      'scope'         => 'Aplica a la compra de medicamentos y dispositivos medicos.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolucion 1403 de 2007 - Servicio Farmaceutico.',
          'Politicas internas de compras.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Orden de compra: documento que formaliza la adquisicion.',
          'Proveedor aprobado: proveedor evaluado y aceptado.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Responsable de compras: ejecutar la adquisicion.',
          'Direccion tecnica: validar especificaciones tecnicas.',
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
            'activity' => '7.1 Programacion de necesidades',
            'description' => 'Revisar inventarios y definir cantidades a comprar.',
            'responsible' => 'Responsable de compras',
            'records' => 'Programacion de compras',
          ],
          [
            'activity' => '7.2 Cotizacion y seleccion',
            'description' => 'Solicitar y evaluar cotizaciones a proveedores.',
            'responsible' => 'Responsable de compras',
            'records' => 'Registro de cotizaciones',
          ],
          [
            'activity' => '7.3 Emision de orden',
            'description' => 'Emitir orden de compra y hacer seguimiento a la entrega.',
            'responsible' => 'Responsable de compras',
            'records' => 'Orden de compra',
          ],
        ]
      ),
      'slug' => 'adquisicion-de-medicamentos-y-dispositivos-medicos',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'orden_compra',
          'registro_cotizaciones',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Formato de solicitud de compra',
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
    //Procedimiento de Recepcion de Medicamentos y Dispositivos Medicos
    [
      'title'         => 'Procedimiento de Recepcion de Medicamentos y Dispositivos Medicos',
      'process_id'    => 'M-RT',
      'document_category_id' => 'PR',
      'objective'     => 'Verificar que los productos recibidos cumplan requisitos tecnicos, sanitarios y administrativos.',
      'scope'         => 'Aplica a la recepcion administrativa y tecnica de productos.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolucion 1403 de 2007 - Servicio Farmaceutico.',
          'Buenas practicas de almacenamiento y transporte.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Recepcion administrativa: verificacion de factura y cantidades.',
          'Recepcion tecnica: inspeccion de calidad y condiciones.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Responsable de recepcion: ejecutar verificaciones.',
          'Direccion tecnica: decidir aceptacion o rechazo.',
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
            'activity' => '7.1 Recepcion administrativa',
            'description' => 'Verificar factura, cantidades, lotes y referencias.',
            'responsible' => 'Responsable de recepcion',
            'records' => 'Registro de recepcion administrativa',
          ],
          [
            'activity' => '7.2 Recepcion tecnica',
            'description' => 'Inspeccionar empaque, rotulado, registro sanitario y cadena de frio.',
            'responsible' => 'Responsable de recepcion',
            'records' => 'Registro de recepcion tecnica',
          ],
          [
            'activity' => '7.3 Registro y decision',
            'description' => 'Registrar resultados y definir aceptacion o rechazo.',
            'responsible' => 'Direccion tecnica',
            'records' => 'Acta de recepcion tecnica',
          ],
        ]
      ),
      'slug' => 'recepcion-de-medicamentos-y-dispositivos-medicos',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'acta_recepcion_tecnica',
          'registro_no_conformidades_recepcion',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Checklist de recepcion tecnica',
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
    //Procedimiento de Almacenamiento de Medicamentos y Dispositivos Medicos
    [
      'title'         => 'Procedimiento de Almacenamiento de Medicamentos y Dispositivos Medicos',
      'process_id'    => 'M-AT',
      'document_category_id' => 'PR',
      'objective'     => 'Conservar los productos en condiciones adecuadas de temperatura, humedad, orden y seguridad.',
      'scope'         => 'Aplica a todas las areas de almacenamiento del establecimiento.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolucion 1403 de 2007 - Servicio Farmaceutico.',
          'Buenas practicas de almacenamiento.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'PEPS/FEFO: metodo de rotacion de inventarios.',
          'Condiciones ambientales: temperatura y humedad controladas.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Responsable de almacenamiento: organizar y monitorear condiciones.',
          'Direccion tecnica: verificar cumplimiento.',
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
            'activity' => '7.1 Ubicacion y organizacion',
            'description' => 'Ordenar productos por categoria y riesgo, evitando confusiones.',
            'responsible' => 'Responsable de almacenamiento',
            'records' => 'Registro de ubicaciones',
          ],
          [
            'activity' => '7.2 Monitoreo ambiental',
            'description' => 'Registrar temperatura y humedad segun la frecuencia definida.',
            'responsible' => 'Responsable de almacenamiento',
            'records' => 'Registro de temperatura y humedad',
          ],
          [
            'activity' => '7.3 Control de vencimientos',
            'description' => 'Aplicar FEFO y gestionar productos proximos a vencer.',
            'responsible' => 'Responsable de almacenamiento',
            'records' => 'Control de vencimientos',
          ],
        ]
      ),
      'slug' => 'almacenamiento-de-medicamentos-y-dispositivos-medicos',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'registro_temperatura_humedad',
          'control_vencimientos',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Plan de almacenamiento y rotacion',
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
    //Procedimiento de Dispensacion de Medicamentos y Dispositivos Medicos
    [
      'title'         => 'Procedimiento de Dispensacion de Medicamentos y Dispositivos Medicos',
      'process_id'    => 'M-DP',
      'document_category_id' => 'PR',
      'objective'     => 'Garantizar la entrega segura y la orientacion adecuada al usuario.',
      'scope'         => 'Aplica a la dispensacion con o sin formula medica.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolucion 1403 de 2007 - Servicio Farmaceutico.',
          'Buenas practicas de dispensacion.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Dispensacion: entrega de medicamentos al usuario.',
          'Orientacion: informacion para el uso seguro y efectivo.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Dispensador: ejecutar la entrega y orientacion.',
          'Direccion tecnica: supervisar el cumplimiento.',
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
            'activity' => '7.1 Validacion',
            'description' => 'Verificar formula, identidad del usuario y requisitos legales.',
            'responsible' => 'Dispensador',
            'records' => 'Registro de validacion',
          ],
          [
            'activity' => '7.2 Entrega y orientacion',
            'description' => 'Entregar el producto e informar dosis, uso y cuidados.',
            'responsible' => 'Dispensador',
            'records' => 'Registro de orientacion',
          ],
          [
            'activity' => '7.3 Registro',
            'description' => 'Registrar la dispensacion y novedades.',
            'responsible' => 'Dispensador',
            'records' => 'Registro de dispensacion',
          ],
        ]
      ),
      'slug' => 'dispensacion-de-medicamentos-y-dispositivos-medicos',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'registro_dispensacion',
          'registro_orientacion_usuario',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Guia de dispensacion',
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
    //Procedimiento de Devolucion de Medicamentos y Dispositivos Medicos
    [
      'title'         => 'Procedimiento de Devolucion de Medicamentos y Dispositivos Medicos',
      'process_id'    => 'M-DV',
      'document_category_id' => 'PR',
      'objective'     => 'Gestionar devoluciones y disposicion final de productos no aptos o retirados.',
      'scope'         => 'Aplica a devoluciones por vencimiento, retiro del mercado o no conformidad.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolucion 1403 de 2007 - Servicio Farmaceutico.',
          'Normativa ambiental vigente para residuos.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Devolucion: retorno de un producto a proveedor o a disposicion final.',
          'Cuarentena: retencion temporal para analisis.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Responsable de devoluciones: ejecutar el proceso.',
          'Direccion tecnica: aprobar decisiones de disposicion.',
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
            'activity' => '7.1 Recepcion de devoluciones',
            'description' => 'Recibir, clasificar y registrar productos devueltos.',
            'responsible' => 'Responsable de devoluciones',
            'records' => 'Registro de devoluciones',
          ],
          [
            'activity' => '7.2 Definicion de destino',
            'description' => 'Determinar reintegro, devolucion a proveedor o disposicion final.',
            'responsible' => 'Direccion tecnica',
            'records' => 'Acta de devolucion',
          ],
          [
            'activity' => '7.3 Disposicion final',
            'description' => 'Coordinar entrega a gestor autorizado y conservar soportes.',
            'responsible' => 'Responsable de devoluciones',
            'records' => 'Acta de disposicion final',
          ],
        ]
      ),
      'slug' => 'devolucion-de-medicamentos-y-dispositivos-medicos',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'acta_devolucion',
          'acta_disposicion_final',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Formato de devoluciones',
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
    //Procedimiento de Farmacovigilancia
    [
      'title'         => 'Procedimiento de Farmacovigilancia',
      'process_id'    => 'M-DP',
      'document_category_id' => 'PR',
      'objective'     => 'Establecer el reporte y seguimiento de eventos adversos y alertas sanitarias.',
      'scope'         => 'Aplica a la deteccion, registro y reporte de eventos adversos.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolucion 1403 de 2007 - Servicio Farmaceutico.',
          'Lineamientos de farmacovigilancia del INVIMA.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Evento adverso: efecto no deseado asociado al uso de un medicamento.',
          'Alerta sanitaria: comunicacion oficial sobre riesgos.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Dispensador: identificar y registrar eventos.',
          'Direccion tecnica: reportar a la autoridad sanitaria.',
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
            'activity' => '7.1 Identificacion',
            'description' => 'Detectar eventos adversos o alertas sanitarias.',
            'responsible' => 'Dispensador',
            'records' => 'Registro de evento adverso',
          ],
          [
            'activity' => '7.2 Registro y analisis',
            'description' => 'Documentar el caso y evaluar su gravedad.',
            'responsible' => 'Direccion tecnica',
            'records' => 'Formato de reporte',
          ],
          [
            'activity' => '7.3 Reporte externo',
            'description' => 'Reportar a la autoridad competente y hacer seguimiento.',
            'responsible' => 'Direccion tecnica',
            'records' => 'Reporte de farmacovigilancia',
          ],
        ]
      ),
      'slug' => 'farmacovigilancia',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'registro_eventos_adversos',
          'reporte_farmacovigilancia',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Formato de reporte de eventos adversos',
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
    //Procedimiento de Manejo de Productos Refrigerados
    [
      'title'         => 'Procedimiento de Manejo de Productos Refrigerados',
      'process_id'    => 'M-AT',
      'document_category_id' => 'PR',
      'objective'     => 'Garantizar la conservacion de productos que requieren cadena de frio.',
      'scope'         => 'Aplica a la recepcion, almacenamiento y control de productos refrigerados.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolucion 1403 de 2007 - Servicio Farmaceutico.',
          'Recomendaciones del fabricante.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Cadena de frio: mantenimiento de temperatura controlada.',
          'Desviacion: salida del rango permitido.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Responsable de almacenamiento: monitorear condiciones.',
          'Direccion tecnica: decidir acciones ante desviaciones.',
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
            'activity' => '7.1 Recepcion en frio',
            'description' => 'Verificar condiciones de transporte y temperatura al recibir.',
            'responsible' => 'Responsable de recepcion',
            'records' => 'Registro de recepcion en frio',
          ],
          [
            'activity' => '7.2 Almacenamiento y monitoreo',
            'description' => 'Conservar entre rangos establecidos y registrar temperaturas.',
            'responsible' => 'Responsable de almacenamiento',
            'records' => 'Registro de temperatura',
          ],
          [
            'activity' => '7.3 Acciones por desviaciones',
            'description' => 'Aplicar medidas correctivas y documentar el evento.',
            'responsible' => 'Direccion tecnica',
            'records' => 'Registro de desviaciones',
          ],
        ]
      ),
      'slug' => 'manejo-productos-refrigerados',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'registro_temperatura_refrigerados',
          'registro_desviaciones_cadena_frio',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Planilla de control de temperatura',
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
    //Plan de Emergencia para Cadena de Frio
    [
      'title'         => 'Plan de Emergencia para Cadena de Frio',
      'process_id'    => 'M-AT',
      'document_category_id' => 'PR',
      'objective'     => 'Definir acciones de contingencia para mantener la cadena de frio ante fallas.',
      'scope'         => 'Aplica a fallas electricas, averias o interrupciones del sistema de refrigeracion.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolucion 1403 de 2007 - Servicio Farmaceutico.',
          'Lineamientos internos de contingencia.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Contingencia: evento que afecta la conservacion adecuada.',
          'Accion correctiva: medida para recuperar el control.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Direccion tecnica: activar el plan y comunicar.',
          'Responsable de almacenamiento: ejecutar medidas de contingencia.',
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
            'activity' => '7.1 Activacion del plan',
            'description' => 'Notificar la falla y declarar la contingencia.',
            'responsible' => 'Direccion tecnica',
            'records' => 'Acta de activacion',
          ],
          [
            'activity' => '7.2 Medidas de contencion',
            'description' => 'Trasladar productos a equipos alternos o contenedores termicos.',
            'responsible' => 'Responsable de almacenamiento',
            'records' => 'Registro de traslado',
          ],
          [
            'activity' => '7.3 Cierre y verificacion',
            'description' => 'Verificar condiciones y cerrar la contingencia.',
            'responsible' => 'Direccion tecnica',
            'records' => 'Acta de cierre',
          ],
        ]
      ),
      'slug' => 'plan-de-emergencia-cadena-de-frio',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'acta_contingencia_cadena_frio',
          'registro_contingencia_cadena_frio',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Checklist de contingencia cadena de frio',
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
    //Gestion de Devoluciones Posconsumo
    [
      'title'         => 'Procedimiento de Gestion de Devoluciones Posconsumo',
      'process_id'    => 'M-DV',
      'document_category_id' => 'PR',
      'objective'     => 'Gestionar la recoleccion y disposicion de medicamentos vencidos o no usados por usuarios.',
      'scope'         => 'Aplica a la recepcion, almacenamiento temporal y entrega a gestores autorizados.',
      'references' => array_map(
        fn($item) => ['title' => $item],
        [
          'Resolucion 1403 de 2007 - Servicio Farmaceutico.',
          'Normativa ambiental aplicable a posconsumo.',
        ]
      ),
      'terms' => array_map(
        fn($item) => ['definition' => $item],
        [
          'Posconsumo: devolucion de medicamentos no utilizados.',
          'Gestor autorizado: entidad habilitada para disposicion final.',
        ]
      ),
      'responsibilities' => array_map(
        fn($item) => ['responsibility' => $item],
        [
          'Responsable de devoluciones: ejecutar el plan posconsumo.',
          'Direccion tecnica: verificar cumplimiento normativo.',
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
            'activity' => '7.1 Divulgacion y recoleccion',
            'description' => 'Informar a usuarios y habilitar puntos de recoleccion.',
            'responsible' => 'Responsable de devoluciones',
            'records' => 'Registro de divulgacion',
          ],
          [
            'activity' => '7.2 Almacenamiento temporal',
            'description' => 'Conservar los residuos en area definida y senalizada.',
            'responsible' => 'Responsable de devoluciones',
            'records' => 'Registro de almacenamiento temporal',
          ],
          [
            'activity' => '7.3 Entrega a gestor',
            'description' => 'Entregar los residuos y conservar actas de disposicion.',
            'responsible' => 'Direccion tecnica',
            'records' => 'Acta de entrega a gestor',
          ],
        ]
      ),
      'slug' => 'gestion-de-devoluciones',
      'records' => array_map(
        fn($item) => ['record' => $item],
        [
          'registro_posconsumo',
          'acta_entrega_gestor',
        ]
      ),
      'annexes' => array_map(
        fn($item) => ['annexe' => $item],
        [
          'Plan posconsumo vigente',
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
