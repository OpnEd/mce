<?php

return [

  'spill-cleanup-procedure' => [
    [
      'title'         => 'Procedimiento de Limpieza de Derrames',
      'process_id'    => 'A-SS',   // Proceso de Apoyo - Seguridad y Salud
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
  ],

];
