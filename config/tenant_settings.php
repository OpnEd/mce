<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Datos generales de la Droguería
    |--------------------------------------------------------------------------
    */

    'mission' => <<<'TXT'
Como establecimiento farmacéutico comprometido con la salud de la comunidad,
nuestra misión es brindar un servicio profesional y humano en la selección,
adquisición, recepción, almacenamiento, distribución y dispensación de
medicamentos y dispositivos médicos, garantizando su calidad, seguridad y
oportunidad, en estricto cumplimiento de la normativa nacional y bajo los
principios de ética, transparencia y responsabilidad social.
TXT,

    'vision' => <<<'TXT'
Para 2028 seremos reconocidos como la droguería líder en gestión integral
del servicio farmacéutico en nuestra región, destacándonos por la excelencia
operativa, la innovación en procesos, el acompañamiento al paciente y el
fortalecimiento continuo de nuestras competencias técnicas y humanas.
TXT,

    'quality_policy' => [

        // Declaración general de la política
        'statement' => <<<'TXT'
En nuestra droguería implementamos un Sistema de Gestión de la Calidad conforme
al Modelo de Gestión del Servicio Farmacéutico (Resolución 1403 de 2007), que
promueve la eficacia y seguridad en cada uno de nuestros procesos misionales,
con un enfoque de mejora continua y formación permanente del equipo humano.
TXT,

        // Objetivos de calidad por proceso misional
        'objectives' => [

            // Selección
            'selection' => 'Lograr que el 100% de las órdenes de compra
            se realicen a proveedores certificados y cumplan con los términos y
            condiciones establecidos en el Manual de Condiciones Esenciales y
            Procedimientos.',

            // Adquisición
            'acquisition' => 'Garantizar una disponibilidad de al menos 95% de los
            productos solicitados por los usuarios.',

            // Recepción
            'reception' => [
                1 => 'Verificar en un 100% de las recepciones que la
            documentación y cantidades entregadas coincidan con lo solicitado
            y no presenten daño o vencimiento.',
                2 => 'Garantizar que el 100% de los medicamentos y dispositivos
            médicos adquiridos cumplan con criterios de calidad, eficacia y legalidad.'
            ],

            // Almacenamiento
            'storage' => 'Mantener el 100% de nuestro inventario en condiciones
            de temperatura y humedad controladas, con indicadores revisados
            diariamente y registros actualizados.',

            // Dispensación
            'dispensation' => [
                1 => 'Alcanzar un 0% de errores de dispensación mediante
            procesos de validación doble y revisión de receta en cada entrega.',
                2 => 'Asegurar que el 100% de las dispensaciones sean
            entregadas al paciente en tiempo y forma, cumpliendo con las buenas
            prácticas de dispensación y con información clara y comprensible sobre
            el uso racional de los medicamentos de venta con fórmula médica más riesgosos.',
            ],

            // Devoluciones
            'returns' => 'Lograr que el 100% de las devoluciones de productos se hagan
            dentro de los plazos establecidos, con documentación completa y
            condiciones adecuadas, garantizando trazabilidad y cumplimiento normativo.',
        ],

        // Compromisos transversales
        'commitments' => [

            // Mejora continua
            'continuous_improvement' => 'Implementar al menos dos proyectos de
            mejora de procesos al año, evaluando su eficacia mediante indicadores
            de desempeño y reportes de resultados.',

            // Capacitación permanente
            'staff_training' => 'Capacitar al 100% del personal cada semestre en
            actualizaciones normativas, buenas prácticas farmacéuticas y atención
            al cliente, garantizando competencias actualizadas y certificadas.',
        ],
    ],

];
