<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MinutesIvcSectionEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This seeder is currently empty. You can add logic to seed the MinutesIvcSectionEntry model.
        // Example:
        // \App\Models\MinutesIvcSectionEntry::factory()->count(10)->create();

        // If you need to seed specific data, you can do so here.
        // For example:
        // \App\Models\MinutesIvcSectionEntry::create([
        //     'minutes_ivc_section_id' => 1,
        //     'apply' => true,
        //     'entry_id' => '9.1MIVC1',
        //     'criticality' => 'major',
        //     'question' => 'Sample description',
        //     'entry_type' => 'informativo',
        //     'links' => json_encode(['link1', 'link2']),
        //     'compliance' => true,
        // ]);

        $entries = [
            // Entries for section 9 Sistema de gestión de calidad
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.1',
                'criticality' => 'major',
                'question' => 'Se evidencia desarrollo e implementación de la Función Administrativa (Planificar, organizar, dirigir coordinar y controlar los servicios relacionados con los medicamentos y dispositivos médicos ofrecidos a los pacientes y a la comunidad en general, con excepción de la prescripción y administración de los medicamentos).',
                'answer' => 'Desarrollamos nuestros procesos de tal forma que los indicadores reflejan el cumplimiento con las expectativas de la comunidad.',
                'entry_type' => 'informativo',
                'links' => null,
                'compliance' => true,
            ],
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.2',
                'criticality' => 'major',
                'question' => 'Se evidencia desarrollo e implementación de la Función Promoción (Impulsar estilos de vida saludables y el uso adecuado de medicamentos y dispositivos médicos.',
                'answer' => 'Como puede verse, tenemos material didáctico al alcance de nuestros usuarios',
                'entry_type' => 'informativo',
                'links' => null,
                'compliance' => true,
            ],
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.3',
                'criticality' => 'major',
                'question' => 'Se evidencia desarrollo e implementación de la Función de Prevención (Prevención de factores de riesgo derivados del uso inadecuado de medicamentos y dispositivos médicos, así como problemas relacionados con su uso. Se refiere a la NO venta de medicamentos que tienen la condicion de "Venta con fórmula médica" sin la presentación de la misma, prestación del servicio de inyectología estrictamente con la presentación de la fórmula médica, la no venta de medicamentos alterados, fraudulentos ni reportados en alertas sanitarias del INVIMA, el no recomentar ni inducir al usuario al consumo de medicamentos).',
                'answer' => '',
                'entry_type' => 'informativo',
                'links' => null,
                'compliance' => true,
            ],
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.4',
                'criticality' => 'major',
                'question' => 'Se cuenta con procedimiento para el reporte de eventos adversos.',
                'answer' => '',
                'entry_type' => 'evidencia',
                'links' => [
                    [
                        'key' => 'Procedimiento de farmacovigilancia',
                        'value' => 'por.definir'
                    ],
                ],
                'compliance' => true,
            ],
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.5',
                'criticality' => 'major',
                'question' => 'De llegarse a presentar, ¿se informa a la comunidad competente los reportes hechos por la comunidad, de los eventos adversos relacionados con el uso de medicamentos?.',
                'answer' => 'Se cuenta con procedimiento y enlace directo a e-reporting',
                'entry_type' => 'evidencia',
                'links' => [
                    [
                        'key' => 'Procedimiento de farmacovigilancia',
                        'value' => 'por.definir'
                    ],
                    [
                        'key' => 'e-reporting',
                        'value' => 'por.definir'
                    ],
                ],
                'compliance' => true,
            ],
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.6',
                'criticality' => 'major',
                'question' => 'Se cuenta con organigrama y manual de funciones del personal que labora en el establecimiento.',
                'answer' => '',
                'entry_type' => 'evidencia',
                'links' => [
                    [
                        'key' => 'organigrama',
                        'value' => 'por.definir'
                    ],
                    [
                        'key' => 'manual de funciones',
                        'value' => 'por.definir'
                    ],
                ],
                'compliance' => true,
            ],
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.7',
                'criticality' => 'major',
                'question' => 'Se tiene un sistema documental que impida el uso accidental de documentos obsoletos o no aprobados. Los documentos están diseñados, revisados, modificados, autorizados, fechados y distribuidas por las personas autorizadas y se mantienen actualizados.',
                'answer' => '',
                'entry_type' => 'evidencia',
                'links' => [
                    [
                        'key' => 'manual de calidad',
                        'value' => 'por.definir'
                    ],
                ],
                'compliance' => true,
            ],
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.8',
                'criticality' => 'major',
                'question' => 'El establecimiento cuenta con una política de calidad documentada. Cuenta con objetivos de calidad que cumplan lo establecido en su politica.',
                'answer' => '',
                'entry_type' => 'evidencia',
                'links' => [
                    [
                        'key' => 'politica de calidad',
                        'value' => 'por.definir'
                    ],
                    [
                        'key' => 'objetivos de calidad',
                        'value' => 'por.definir'
                    ]
                ],
                'compliance' => true,
            ],
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.9',
                'criticality' => 'major',
                'question' => 'El establecimiento ha desarrollado y cuenta con una Misión y una Visión.',
                'answer' => '',
                'entry_type' => 'evidencia',
                'links' => [
                    [
                        'key' => 'mision',
                        'value' => 'por.definir'
                    ],
                    [
                        'key' => 'vision',
                        'value' => 'por.definir'
                    ],
                ],
                'compliance' => true,
            ],
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.10',
                'criticality' => 'major',
                'question' => 'Los procesos propios del establecimiento farmacéutico se encuentran debidamente caracterizados.',
                'answer' => '',
                'entry_type' => 'evidencia',
                'links' => [
                    [
                        'key' => 'Selección',
                        'value' => 'por.definir'
                    ],
                    [
                        'key' => 'Adquisición',
                        'value' => 'por.definir'
                    ],
                    [
                        'key' => 'Recepción',
                        'value' => 'por.definir'
                    ],
                    [
                        'key' => 'Almacenamiento',
                        'value' => 'por.definir'
                    ],
                    [
                        'key' => 'Dispensación',
                        'value' => 'por.definir'
                    ],
                    [
                        'key' => 'Devolución',
                        'value' => 'por.definir'
                    ],
                ],
                'compliance' => true,
            ],
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.11',
                'criticality' => 'major',
                'question' => 'Se muestran los procesos estratégicos y criticos (propios del establecimiento farmacéutico), determinantes de la calidad, su secuencia e interacción (en un mapa de procesos), con base en criterios técnicos previamente definidos.',
                'answer' => '',
                'entry_type' => 'evidencia',
                'links' => [
                    [
                        'key' => 'Mapa de Procesos',
                        'value' => 'por.definir'
                    ],
                ],
                'compliance' => true,
            ],
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.12',
                'criticality' => 'major',
                'question' => 'Las políticas y programas de mejoramiento continuo promueven la capacitación del recuro humano? Se cuenta con mecanismo de programación y procedimiento para la inducción y la capacitación del personal?',
                'answer' => '',
                'entry_type' => 'evidencia',
                'links' => [
                    [
                        'key' => 'Politica de calidad',
                        'value' => 'por.definir'
                    ],
                    [
                        'key' => 'Cronogramas',
                        'value' => 'por.definir'
                    ],
                    [
                        'key' => 'Calendario',
                        'value' => 'por.definir'
                    ],
                ],
                'compliance' => true,
            ],
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.13',
                'criticality' => 'major',
                'question' => 'Se cuenta con registro de capacitación del personal.',
                'answer' => '',
                'entry_type' => 'evidencia',
                'links' => [
                    [
                        'key' => 'Capacitación firmada',
                        'value' => 'por.definir'
                    ],
                    [
                        'key' => 'Respuestas de exámenes',
                        'value' => 'por.definir'
                    ],
                ],
                'compliance' => true,
            ],
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.14',
                'criticality' => 'major',
                'question' => 'Existe un procedimiento documentado para la medición de la satisfacción del usuario? ¿Se cuenta con registros y resultados?',
                'answer' => '',
                'entry_type' => 'evidencia',
                'links' => [
                    [
                        'key' => 'Procedimiento de Evaluación de la Satisfacción del Usuario',
                        'value' => 'por.definir'
                    ],
                    [
                        'key' => 'Indicador de evaluación de la satisfacción',
                        'value' => 'por.definir'
                    ],
                ],
                'compliance' => true,
            ],
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.15',
                'criticality' => 'major',
                'question' => '¿Existe un procedimiento documentado y registros para el control, recepción, clasificación, evaluación y cierre de las quejas presentadas por los usuarios.?',
                'answer' => '',
                'entry_type' => 'evidencia',
                'links' => [
                    [
                        'key' => 'Procedimiento de PQRS',
                        'value' => 'por.definir'
                    ],
                    [
                        'key' => 'Registro de PQRS',
                        'value' => 'por.definir'
                    ],
                ],
                'compliance' => true,
            ],
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.16',
                'criticality' => 'major',
                'question' => '¿Se realiza el seguimiento, análisis y medición de los procesos propios del establecimiento farmacéutico (indicadores de gestión).',
                'answer' => '',
                'entry_type' => 'evidencia',
                'links' => [
                    [
                        'key' => 'Tablero de indicadores de gestión',
                        'value' => 'por.definir'
                    ],
                ],
                'compliance' => true,
            ],
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.17',
                'criticality' => 'major',
                'question' => '¿Cuenta con procedimiento y plan de auditoria / autoinspección interna identificando la frecuencia de estas.?',
                'answer' => '',
                'entry_type' => 'evidencia',
                'links' => [
                    [
                        'key' => 'Procedimiento de autoinspecciones',
                        'value' => 'por.definir'
                    ],
                    [
                        'key' => 'Programa de autoinspecciones',
                        'value' => 'por.definir'
                    ],
                ],
                'compliance' => true,
            ],
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.18',
                'criticality' => 'major',
                'question' => 'Se evidencia procedimiento escrito para el desarrollo de planes de mejora, correcciones, acciones correctives, y los resultados de las mismas.?',
                'answer' => '',
                'entry_type' => 'evidencia',
                'links' => [
                    [
                        'key' => 'Procedimiento para el desarrollo de planes de mejora',
                        'value' => 'por.definir'
                    ],
                    [
                        'key' => 'Lista de planes de mejora',
                        'value' => 'por.definir'
                    ],
                ],
                'compliance' => true,
            ],
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.19',
                'criticality' => 'major',
                'question' => 'Se evalúan y se mantienen bajo control los riesgos de mayor severidad de daño y probabilidad de ocurrencia (Matriz de Riesgos).',
                'answer' => '',
                'entry_type' => 'evidencia',
                'links' => [
                    [
                        'key' => 'Procedimiento de evaluación y gestión de riesgos',
                        'value' => 'por.definir'
                    ],
                    [
                        'key' => 'Matriz de riesgos',
                        'value' => 'por.definir'
                    ],
                ],
                'compliance' => true,
            ],
            [
                'minutes_ivc_section_id' => 29,
                'apply' => true,
                'entry_id' => '9.20',
                'criticality' => 'major',
                'question' => 'Se presentan periódicamente los resultados de indicadores de Gestión de Calidad del Servicio/Establecimiento Farmacéutico?',
                'answer' => '',
                'entry_type' => 'evidencia',
                'links' => [
                    [
                        'key' => 'Tablero de indicadores de gestión',
                        'value' => 'por.definir'
                    ],
                ],
                'compliance' => true,
            ],

        ];

        foreach ($entries as $e) {
            \App\Models\MinutesIvcSectionEntry::updateOrCreate(
                //
                [
                    'minutes_ivc_section_id' => $e['minutes_ivc_section_id'],
                    'apply' => $e['apply'],
                    'entry_id' => $e['entry_id'],
                    'criticality' => $e['criticality'],
                    'question' => $e['question'],
                    'answer' => $e['answer'],
                    'entry_type' => $e['entry_type'],
                    'links' => $e['links'],
                    'compliance' => $e['compliance'],
                ]
            );
        }
    }
}
