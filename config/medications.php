<?php

return [

    /*
    Estructura por clave:
    'clave_unica' => [
        'name' => 'Nombre comercial / genérico',
        'category' => 'Venta con fórmula' | 'Alto riesgo' | 'Venta con fórmula - alto riesgo',
        'reconstitution' => 'Instrucciones de reconstitución si aplica (o null)',
        'dose_measurement' => 'Cómo medir la dosis / advertencias para administración',
        'storage' => 'Condiciones de almacenamiento',
        'disposal' => 'Disposición final recomendada',
        'adherence_warning' => 'Avisos sobre adherencia',
        'adverse_event_warning' => 'Qué hacer ante reacciones adversas',
    ],
    */

    'amoxicilina_500_mg' => [
        'name' => 'Amoxicilina 500 mg (tableta / suspensión)',
        'category' => 'Venta con fórmula',
        'reconstitution' => 'Si es suspensión: reconstituir con el volumen de agua indicado en el envase; agitar antes de usar.',
        'dose_measurement' => 'Seguir estrictamente la dosis indicada por el facultativo; use la jeringa dosificadora o cucharilla incluida para suspensión.',
        'storage' => 'Conservar a temperatura ambiente; suspensión reconstituida en refrigeración si el fabricante lo indica.',
        'disposal' => 'Desechar envases y restos en puntos de recolección; no verter en desagües.',
        'adherence_warning' => 'Complete todo el tratamiento aun si mejora; no comparta antibióticos.',
        'adverse_event_warning' => 'Si aparece erupción, picazón, fiebre o dificultad respiratoria, suspenda y consulte inmediatamente.',
    ],

    'ceftriaxona_1g_iv' => [
        'name' => 'Ceftriaxona 1 g (inyectable)',
        'category' => 'Venta con fórmula - Alto riesgo (vía parenteral)',
        'reconstitution' => 'Reconstituir con el diluyente indicado por el fabricante; usar técnica aséptica. No mezclar en la misma jeringa con soluciones que contengan calcio para neonatos.',
        'dose_measurement' => 'Administrar vía intramuscular o intravenosa según prescripción; verificar volumen y concentración antes de administrar.',
        'storage' => 'Polvo: conservar en lugar seco; solución preparada según instrucciones y desechar si no se usa en el tiempo recomendado.',
        'disposal' => 'Desechar residuos y jeringas en contenedores para objetos punzantes; restos líquidos según normativa local.',
        'adherence_warning' => 'Siga la pauta del profesional; no modificar intervalos sin consulta.',
        'adverse_event_warning' => 'Informe de reacciones alérgicas (urticaria, dificultad respiratoria) o signos de infección secundaria.',
    ],

    'gentamicina_inyectable' => [
        'name' => 'Gentamicina (inyectable)',
        'category' => 'Alto riesgo',
        'reconstitution' => 'Reconstituir con el diluyente indicado; conservar asepsia absoluta en preparación.',
        'dose_measurement' => 'Dosis y frecuencia según prescripción y función renal; usar jeringa calibrada; monitorizar niveles si indicado.',
        'storage' => 'Conservar según especificaciones del fabricante; proteger de la luz según formulación.',
        'disposal' => 'Desechar agujas y restos en contenedor para punzantes; seguir gestión de residuos hospitalarios para sobrantes.',
        'adherence_warning' => 'No interrumpir sin consultar; la dosificación depende de función renal y peso.',
        'adverse_event_warning' => 'Riesgo de nefrotoxicidad y ototoxicidad; reporte tinnitus, mareo, disminución de la audición o cambios en la micción.',
    ],

    'vancomicina' => [
        'name' => 'Vancomicina (inyectable/oral según formulación)',
        'category' => 'Alto riesgo',
        'reconstitution' => 'Seguir instrucciones del fabricante; para infusión IV preparar en la concentracion y volumen prescritos.',
        'dose_measurement' => 'Administrar por infusión lenta IV; evitar bolos. Ajustar dosis según niveles plasmáticos cuando corresponda.',
        'storage' => 'Conservar según prospecto; soluciones para perfusión usar en tiempos descritos.',
        'disposal' => 'Desechar en contenedores para punzantes; restos siguiendo protocolo de residuos peligrosos.',
        'adherence_warning' => 'Siga la pauta completa y no reutilice toques de la misma ampolla entre pacientes.',
        'adverse_event_warning' => 'Reporte erupciones, hipotensión, e hipersensibilidad; posible nefrotoxicidad.',
    ],

    'ciprofloxacino_500_mg' => [
        'name' => 'Ciprofloxacino 500 mg',
        'category' => 'Venta con fórmula',
        'reconstitution' => null,
        'dose_measurement' => 'Tomar con agua; respetar la dosis y duración prescrita por el profesional.',
        'storage' => 'Conservar a temperatura ambiente, protegido de la luz.',
        'disposal' => 'Entregar sobrantes en puntos de recolección; no desechar por el inodoro.',
        'adherence_warning' => 'Complete la pauta y evite exposición al sol intensiva por riesgo de fotosensibilidad.',
        'adverse_event_warning' => 'Si observa dolor articular agudo o hinchazón, suspenda y consulte; consulte sobre riesgo en pacientes jóvenes/ancianos.',
    ],

    'azitromicina_500_mg' => [
        'name' => 'Azitromicina 500 mg',
        'category' => 'Venta con fórmula',
        'reconstitution' => 'Si es suspensión, reconstituir según prospecto.',
        'dose_measurement' => 'Seguir la pauta prescrita; utilizar la jeringa dosificadora para suspensión.',
        'storage' => 'Conservar según prospecto; suspensión reconstituida refrigerar si se indica.',
        'disposal' => 'Entregar sobrantes en la droguería o punto autorizado.',
        'adherence_warning' => 'No interrumpir antes de la duración indicada por el facultativo.',
        'adverse_event_warning' => 'Reporte malestar gastrointestinal severo o reacciones alérgicas.',
    ],

    'insulina_100_ui_ml' => [
        'name' => 'Insulina 100 UI/mL (pluma o vial)',
        'category' => 'Alto riesgo',
        'reconstitution' => 'No reconstituir (a menos que la formulación lo requiera); siga instrucciones del fabricante.',
        'dose_measurement' => 'Medir con pluma dosificadora o jeringa calibrada; nunca usar cucharas caseras. Comprobar unidades prescritas (UI).',
        'storage' => 'Conservar sin abrir en refrigeración 2–8°C; viales abiertos seguir lo indicado en el prospecto (p. ej. 28 días a temperatura ambiente según fabricante).',
        'disposal' => 'Desechar agujas en contenedor para punzantes; embalaje y restos en punto de recolección.',
        'adherence_warning' => 'Siga la pauta y horarios; no ajuste dosis sin consultar. Lleve registro de glucemias si le indicaron.',
        'adverse_event_warning' => 'Si sospecha hipoglucemia (sudor, temblor, confusión) siga el protocolo y consulte a su profesional; notifique cualquier reacción local o sistémica.',
    ],

    'warfarina' => [
        'name' => 'Warfarina (anticoagulante)',
        'category' => 'Alto riesgo',
        'reconstitution' => null,
        'dose_measurement' => 'Tomar exactamente la dosis prescrita; uso de pastillero/jeringa según formato. Mantener control de INR según indicación.',
        'storage' => 'Conservar a temperatura ambiente en envase cerrado.',
        'disposal' => 'Devolver sobrantes a la droguería; no compartir medicamentos anticoagulantes.',
        'adherence_warning' => 'Mantener controles periódicos; evite cambios bruscos de dieta y alcohol sin consultar.',
        'adverse_event_warning' => 'Sangrados, hematomas o sangrado nasal: acudir de inmediato; notifique al profesional y a la droguería.',
    ],

    'enoxaparina' => [
        'name' => 'Enoxaparina (heparina de bajo peso molecular, inyectable)',
        'category' => 'Alto riesgo',
        'reconstitution' => null,
        'dose_measurement' => 'Administrar subcutáneamente según pauta; usar la jeringa precargada o jeringa calibrada indicada.',
        'storage' => 'Conservar en refrigeración o según prospecto; no agitar vigorosamente.',
        'disposal' => 'Desechar jeringas y agujas en contenedor para punzantes; restos según normativa.',
        'adherence_warning' => 'Siga instrucciones de administración; no reaplicar agujas ni compartir el dispositivo.',
        'adverse_event_warning' => 'Sangrado, equímosis o dolor en el sitio de inyección: informe al profesional y a la droguería.',
    ],

    'heparina_sodica' => [
        'name' => 'Heparina sódica (inyección/infusión)',
        'category' => 'Alto riesgo',
        'reconstitution' => 'Preparar según prescripción y con técnica aséptica en centros autorizados.',
        'dose_measurement' => 'Administración controlada en hospital o bajo indicación; no administrar bolos sin supervisión.',
        'storage' => 'Conservar según prospecto.',
        'disposal' => 'Desechar en contenedores para residuos peligrosos; controlar trazabilidad.',
        'adherence_warning' => 'Administrada mayoritariamente en hospital; siga indicaciones de anticoagulación.',
        'adverse_event_warning' => 'Riesgo de sangrado; cualquier hemorragia requiere atención inmediata.',
    ],

    'metotrexato' => [
        'name' => 'Metotrexato (tableta/inyectable)',
        'category' => 'Alto riesgo (inmunosupresor/antineoplásico)',
        'reconstitution' => 'Siga procedimiento para reconstitución si es dosis parenteral; manipule con guantes y precauciones.',
        'dose_measurement' => 'Seguir estrictamente la frecuencia y dosis (semana/diaria según prescripción); usar jeringa o dispositivo indicado.',
        'storage' => 'Conservar según prospecto; manipular con precaución.',
        'disposal' => 'Restos y materiales de administración en contenedores especiales; no desechar en basura común.',
        'adherence_warning' => 'No exceder la dosis; consulte sobre interacciones y medidas de protección.',
        'adverse_event_warning' => 'Síntomas de toxicidad (mucositis, sangrado, fiebre) requieren consulta urgente; notifique cualquier evento.',
    ],

    'litio_carbonato' => [
        'name' => 'Litio (carbonato de litio)',
        'category' => 'Alto riesgo',
        'reconstitution' => null,
        'dose_measurement' => 'Tomar estríctamente la dosis prescrita; monitorización de niveles séricos es obligatoria.',
        'storage' => 'Conservar a temperatura ambiente, alejado de la humedad.',
        'disposal' => 'Devolver sobrantes a punto autorizado.',
        'adherence_warning' => 'No interrumpir ni variar dosis sin control; mantener hidratación adecuada y control de electrolitos.',
        'adverse_event_warning' => 'Signos de intoxicación (temblor, sedación, vómito) requieren atención inmediata; notifique al profesional.',
    ],

    'digoxina' => [
        'name' => 'Digoxina',
        'category' => 'Alto riesgo',
        'reconstitution' => null,
        'dose_measurement' => 'Seguir prescripción exacta; cuidado con interacciones que alteran niveles.',
        'storage' => 'Conservar según prospecto.',
        'disposal' => 'Entregar sobrantes en punto autorizado.',
        'adherence_warning' => 'Mantener horarios de toma; monitorización con analíticas cuando lo indique el médico.',
        'adverse_event_warning' => 'Náuseas, visión borrosa o arritmias: consulte de inmediato.',
    ],

    'amiodarona' => [
        'name' => 'Amiodarona',
        'category' => 'Venta con fórmula - Alto riesgo',
        'reconstitution' => null,
        'dose_measurement' => 'Seguir prescripción y monitorización cardiológica; especial cuidado en dosis de carga vs mantenimiento.',
        'storage' => 'Conservar a temperatura ambiente, protegido de la luz.',
        'disposal' => 'Restos en punto autorizado.',
        'adherence_warning' => 'No interrumpir sin indicación; informar de signos respiratorios o tiroideos.',
        'adverse_event_warning' => 'Toxicidad pulmonar/tiroidea y fototoxicidad: acuda si empeora la respiración o aparece coloración cutánea.',
    ],

    'carbamazepina' => [
        'name' => 'Carbamazepina',
        'category' => 'Venta con fórmula - Alto riesgo',
        'reconstitution' => null,
        'dose_measurement' => 'Tomar según prescripción; no triturar liberación prolongada; usar la forma y dosis prescrita.',
        'storage' => 'Temperatura ambiente.',
        'disposal' => 'Devolver sobrantes a droguería.',
        'adherence_warning' => 'No interrumpir súbitamente; riesgo de crisis si se suspende sin control.',
        'adverse_event_warning' => 'Erupciones cutáneas severas, fiebre o signos de hipersensibilidad requieren suspensión y consulta.',
    ],

    'acido_valproico' => [
        'name' => 'Ácido valproico (valproato)',
        'category' => 'Venta con fórmula - Alto riesgo',
        'reconstitution' => null,
        'dose_measurement' => 'Seguir prescripción; medir con dispositivo incluido si es suspensión.',
        'storage' => 'Conservar según prospecto.',
        'disposal' => 'Punto de recolección de medicamentos.',
        'adherence_warning' => 'No interrumpir la medicación sin consultar; control de niveles si indicado.',
        'adverse_event_warning' => 'Signos de hepatotoxicidad o malestar grave deben ser notificados inmediatamente.',
    ],

    'fenitoina' => [
        'name' => 'Fenitoína',
        'category' => 'Venta con fórmula - Alto riesgo',
        'reconstitution' => null,
        'dose_measurement' => 'Seguir la dosis y forma prescrita; monitorización de niveles si lo indica el médico.',
        'storage' => 'Conservar en envase cerrado a temperatura ambiente.',
        'disposal' => 'Entregar sobrantes en punto autorizado.',
        'adherence_warning' => 'No suspenda bruscamente; consulte antes de cambiar marcas o formulaciones.',
        'adverse_event_warning' => 'Sedación excesiva, ataxia o erupciones requieren consulta urgente.',
    ],

    'clozapina' => [
        'name' => 'Clozapina',
        'category' => 'Alto riesgo (antipsicótico de manejo especializado)',
        'reconstitution' => null,
        'dose_measurement' => 'Administración según plan y controles hematológicos obligatorios; dispensar solo con indicación y monitorización.',
        'storage' => 'Conservar según prospecto.',
        'disposal' => 'Devolver sobrantes a punto autorizado.',
        'adherence_warning' => 'Control estricto de neutrófilos: informe signos de infección o fiebre inmediatamente.',
        'adverse_event_warning' => 'Fiebre, dolor de garganta o cualquier síntoma sugestivo de agranulocitosis debe ser reportado y evaluado de inmediato.',
    ],

    'morfina' => [
        'name' => 'Morfina (opioide)',
        'category' => 'Alto riesgo',
        'reconstitution' => null,
        'dose_measurement' => 'Administrar según prescripción; usar dispositivos de dosificación seguros para comprimidos o soluciones.',
        'storage' => 'Conservar en lugar seguro, fuera del alcance de terceros.',
        'disposal' => 'Entregar sobrantes en punto autorizado; controlar desechos por riesgo de abuso.',
        'adherence_warning' => 'No aumentar dosis por cuenta propia; riesgo de depresión respiratoria.',
        'adverse_event_warning' => 'Somnolencia profunda, respiración lenta o confusión: buscar ayuda médica inmediata.',
    ],

    'fentanilo' => [
        'name' => 'Fentanilo (parches/inyectable)',
        'category' => 'Alto riesgo',
        'reconstitution' => null,
        'dose_measurement' => 'Seguir exactamente la formulación y la dosificación; los parches requieren manejo especial y no deben cortarse.',
        'storage' => 'Mantener en lugar seguro y a temperatura según prospecto.',
        'disposal' => 'Parches usados deben doblarse y desechar en punto seguro; restos en contenedor para residuos controlados.',
        'adherence_warning' => 'Evitar exposición accidental a niños o mascotas; no tomar dosis extra sin supervisión.',
        'adverse_event_warning' => 'Depresión respiratoria y sedación severa requieren atención inmediata; notifique reacciones adversas.',
    ],

    'tramadol' => [
        'name' => 'Tramadol',
        'category' => 'Venta con fórmula - Alto riesgo (analgésico opioide)',
        'reconstitution' => null,
        'dose_measurement' => 'Tomar según prescripción y no mezclar con alcohol o sedantes.',
        'storage' => 'Conservar a temperatura ambiente, fuera del alcance de menores.',
        'disposal' => 'Devolver sobrantes a punto autorizado.',
        'adherence_warning' => 'No exceder dosis prescrita; riesgo de dependencia.',
        'adverse_event_warning' => 'Somnolencia intensa, mareos o signos de alergia deben ser consultados.',
    ],

    'alprazolam' => [
        'name' => 'Alprazolam',
        'category' => 'Venta con fórmula (ansiolítico)',
        'reconstitution' => null,
        'dose_measurement' => 'Tomar la dosis prescrita; evitar la combinación con alcohol y opiáceos.',
        'storage' => 'Lugar seco y fresco, fuera del alcance de niños.',
        'disposal' => 'Entregar sobrantes en punto de recolección.',
        'adherence_warning' => 'No suspender bruscamente tras uso prolongado; riesgo de síndrome de abstinencia.',
        'adverse_event_warning' => 'Sedación intensa o depresión respiratoria requiere consulta urgente; informe reacciones adversas.',
    ],

    'diazepam' => [
        'name' => 'Diazepam',
        'category' => 'Venta con fórmula',
        'reconstitution' => null,
        'dose_measurement' => 'Usar según prescripción; cuidado con la combinación con otros depresores del SNC.',
        'storage' => 'Conservar a temperatura ambiente.',
        'disposal' => 'Devolver sobrantes a punto autorizado.',
        'adherence_warning' => 'Evitar alcohol; no modificar dosis sin consultar.',
        'adverse_event_warning' => 'Somnolencia extrema, dificultades respiratorias o confusión: consulte inmediatamente.',
    ],

    'cloruro_potasio' => [
        'name' => 'Cloruro de potasio (KCl) - formulaciones orales e IV',
        'category' => 'Alto riesgo (electrolito IV)',
        'reconstitution' => 'Para formulaciones parenterales siga diluciones indicadas; no administrar en bolo IV sin supervisión.',
        'dose_measurement' => 'Medir con precisión; verificar concentración y vía. No administrar concentraciones altas por vía periférica sin indicación.',
        'storage' => 'Conservar según prospecto. Mantener fuera del alcance de niños.',
        'disposal' => 'Residuos según normativa de PGRA.',
        'adherence_warning' => 'Tomar con alimentos si indica el médico; no autofabricar soluciones.',
        'adverse_event_warning' => 'Sensación de palpitaciones, debilidad muscular o parestesias: buscar atención urgente.',
    ],

    'prednisona' => [
        'name' => 'Prednisona / Prednisolona (corticosteroide)',
        'category' => 'Venta con fórmula',
        'reconstitution' => null,
        'dose_measurement' => 'Tomar la dosis exacta indicada y completar el esquema; para pautas cortas siga indicación médica.',
        'storage' => 'A temperatura ambiente.',
        'disposal' => 'Punto de recolección de medicamentos.',
        'adherence_warning' => 'No suspender bruscamente si uso prolongado; discutir pauta de retirada con el prescriptor.',
        'adverse_event_warning' => 'Signos de infección, cambios de humor o hinchazón prolongada: comunicar al médico.',
    ],

    'levotiroxina' => [
        'name' => 'Levotiroxina (hormona tiroidea)',
        'category' => 'Venta con fórmula',
        'reconstitution' => null,
        'dose_measurement' => 'Tomar en ayunas y separar de alimentos/suplementos que interfieran; seguir pauta y horario prescrito.',
        'storage' => 'Conservar en lugar seco y fresco, lejos de la luz.',
        'disposal' => 'Devolver sobrantes en punto autorizado.',
        'adherence_warning' => 'No cambiar la marca sin consultar; mantenga controles periódicos de función tiroidea.',
        'adverse_event_warning' => 'Taquicardia, temblor o pérdida de peso rápida: consulte al profesional.',
    ],

    'isoniazida' => [
        'name' => 'Isoniazida (tratamiento antituberculoso)',
        'category' => 'Venta con fórmula',
        'reconstitution' => null,
        'dose_measurement' => 'Seguir esquema de tratamiento completo; uso estrictamente supervisado por programa de TB.',
        'storage' => 'Conservar a temperatura ambiente según prospecto.',
        'disposal' => 'Entregar sobrantes en punto autorizado; no compartir medicamentos antituberculosos.',
        'adherence_warning' => 'Completar esquema para evitar resistencia; tomar según horario indicado.',
        'adverse_event_warning' => 'Signos de hepatotoxicidad (ictericia, dolor abdominal) deben ser consultados de inmediato.',
    ],

    'rifampicina' => [
        'name' => 'Rifampicina (antituberculoso)',
        'category' => 'Venta con fórmula',
        'reconstitution' => null,
        'dose_measurement' => 'Seguir oficialmente el esquema de tratamiento; puede colorear fluidos corporales (orina, lágrimas) de color rojo-naranja.',
        'storage' => 'Conservar de acuerdo con prospecto.',
        'disposal' => 'Devolver sobrantes; no arrojar a desagüe.',
        'adherence_warning' => 'Completar todo el tratamiento; advertir sobre interacciones medicamentosas.',
        'adverse_event_warning' => 'Ictericia u orina muy oscura: acudir inmediatamente.',
    ],

    'metformina' => [
        'name' => 'Metformina',
        'category' => 'Venta con fórmula',
        'reconstitution' => null,
        'dose_measurement' => 'Tomar según prescripción; dosis y forma (liberación prolongada o inmediata) según indicación.',
        'storage' => 'Conservar a temperatura ambiente.',
        'disposal' => 'Entregar sobrantes en punto acreditado.',
        'adherence_warning' => 'Mantener controles de glucemia y función renal según indicación.',
        'adverse_event_warning' => 'Malestar gastrointestinal frecuente; acuda si hay dolor abdominal intenso o sospecha de acidosis.',
    ],

    'azatioprina' => [
        'name' => 'Azatioprina (inmunosupresor)',
        'category' => 'Alto riesgo',
        'reconstitution' => null,
        'dose_measurement' => 'Seguir plan y dosis indicados; control hematológico regular es necesario.',
        'storage' => 'Conservar según prospecto.',
        'disposal' => 'Restos y envases en punto autoritativo.',
        'adherence_warning' => 'No interrumpir sin indicación; mantenga controles periódicos.',
        'adverse_event_warning' => 'Fiebre o síntomas de infección han de notificarse de inmediato; posible supresión medular.',
    ],

];
