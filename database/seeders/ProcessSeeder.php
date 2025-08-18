<?php

namespace Database\Seeders;

use App\Models\Process;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1
        Process::create([
            'process_type_id' => 3,
            'records' => [
                'Calibración de equipos',
                'Limpieza y desinfección de tantes que agua',
                'Control de plagas'
                ],
            'code' => 'A-SM',
            'name' => 'Saneamiento y Mantenimiento',
            'description' => 'Mantenimiento de instalaciones, equipos. Limpieza, sanitización. Adquisiciones no farmacéuticas.',
            'suppliers' => ['Todos los procesos'],
            'inputs' => ['Requerimientos'],
            'procedures' => ['Análisis de requerimientos, elaboración de presupuestos, compras, contrataciones.'],
            'outputs' => ['Reparados, manutenciones'],
            'clients' => ['Todos los procesos'],
        ]);

        // 2
        Process::create([
            'process_type_id' => 1,
            'records' => '',
            'code' => 'A-AR',
            'name' => 'Asuntos regulatorios',
            'description' => 'Trámites legales',
            'suppliers' => ['Todos los procesos'],
            'inputs' => ['Normatividad legal'],
            'procedures' => ['Revisión normativa'],
            'outputs' => ['Documentos legales'],
            'clients' => ['Entes regulatorios'],
        ]);

        // 3
        Process::create([
            'process_type_id' => 1,
            'records' => [
                'Planes de mejora',
                'Acciones correctivas o preventivas',
                ],
            'code' => 'D-PG',
            'name' => 'Planeación y gerencia',
            'description' => 'Desarrolla plataforma estratégica, evalúa y hace seguimiento',
            'suppliers' => ['Todos los procesos'],
            'inputs' => ['Indicadores de gestión'],
            'procedures' => ['Analisis dofa, analisis de riesgos, estudio de outputs de evaluación'],
            'outputs' => ['Políticas, directrices, planes, matriz de riesgos'],
            'clients' => ['Todos los procesos'],
        ]);

        // 4
        Process::create([
            'process_type_id' => 4,
            'records' => [
                'Programa de auditoría',
                'Planes de auditorías',
                'Informes de auditorías',
                ],
            'code' => 'E-RS',
            'name' => 'Evaluación, retroalimentación y seguimiento',
            'description' => 'Recolecta datos, información, evidencia; evalú y emite conceptos.',
            'suppliers' => ['Todos los procesos'],
            'inputs' => ['Datos relativos a evidencia de desempeño'],
            'procedures' => ['Auitorías, entrevistas.'],
            'outputs' => ['Informes de auditorías'],
            'clients' => ['Todos los procesos'],
        ]);

        // 5
        Process::create([
            'process_type_id' => 2,
            'records' => [
                'Listado básico de medicamentos',
                'Selección y evaluación de proveedores',
                ],
            'code' => 'M-SL',
            'name' => 'Selección',
            'description' => 'Selección de proveedores, medicamentos y dispositivos médicos.',
            'suppliers' => ['Usuarios del establecimiento, normatividad'],
            'inputs' => ['Información de consumos, información sobre proveedores'],
            'procedures' => ['Procesamiento de datos de consumos históricos y calidad, procesamiento de datos de desempeño de proveedores.'],
            'outputs' => ['Listado básico de medicamentos y dispositivos médicos. Evaluaciones de proveedores'],
            'clients' => ['Adquisición'],
        ]);

        // 6
        Process::create([
            'process_type_id' => 3,
            'records' => ['Por definir'],
            'code' => 'A-RH',
            'name' => 'Gestión de Recurso Humano',
            'description' => 'Contratación, capacitación, salud, seguridad en el trabajo.',
            'suppliers' => ['Todos los procesos'],
            'inputs' => ['Normatividad legal, Curriculums, Evaluaciones de desempeño, Requerimientos de capacitación'],
            'procedures' => ['Por definir.'],
            'outputs' => ['Por definir'],
            'clients' => ['Todos los procesos'],
        ]);

        // 7
        Process::create([
            'process_type_id' => 3,
            'records' => ['Por definir'],
            'code' => 'A-BL',
            'name' => 'Inducción y Capacitación',
            'description' => 'Almacenamiento de material informativo y didáctico.',
            'suppliers' => ['Todos los procesos'],
            'inputs' => ['Necesidades de formación y desarrollo de competencias'],
            'procedures' => ['Sin definir.'],
            'outputs' => ['Material didáctico e informativo'],
            'clients' => ['Todos los procesos'],
        ]);

        // 8
        Process::create([
            'process_type_id' => 3,
            'records' => ['Orden de compra'],
            'code' => 'M-AQ',
            'name' => 'Adquisición',
            'description' => 'Solicita a los proveedores los productos cuyas existencias deben reponerse.',
            'suppliers' => ['Procesos de Selección y Dispensación'],
            'inputs' => ['Listado básico de medicamentos y dispositivos médicos, solicitudes de usuarios, consumos históricos'],
            'procedures' => ['Por definir.'],
            'outputs' => ['Productos solicitados, facturas'],
            'clients' => ['Recepción técnica'],
        ]);

        // 9
        Process::create([
            'process_type_id' => 3,
            'records' => ['Acta de Recepción téncica y administrativa'],
            'code' => 'M-RT',
            'name' => 'Recepción técnica',
            'description' => 'Inspección de productos a la entrada para garantizar estándares mínimos de calidad.',
            'suppliers' => ['Adquisición'],
            'inputs' => ['Productos, especificaciones técnicas, políticas de calidad, normatividad vigente'],
            'procedures' => ['Inspección de productos y diligenciamiento de formulario para recepción técnica. Archivo de documentos.'],
            'outputs' => ['Productos inspeccionados, registros de recepción técnica'],
            'clients' => ['Almacenamiento, Dispensación'],
        ]);

        // 10
        Process::create([
            'process_type_id' => 3,
            'records' => [
                'Temperatura y humedad',
                'Limpieza y sanitización',
                ],
            'code' => 'M-AT',
            'name' => 'Almacenamiento',
            'description' => 'Almacenar los productos hasta el momento de su dispensación, conservando su calidad.',
            'suppliers' => ['Recepción técnica'],
            'inputs' => ['Productos inspeccionados, políticas de almacenamiento y control de fechas de vencimiento'],
            'procedures' => ['Por definir.'],
            'outputs' => ['Conservación de la calidad de los productos'],
            'clients' => ['Dispensación'],
        ]);

        // 11
        Process::create([
            'process_type_id' => 3,
            'records' => [
                'Educación al usuario',
                'Satisfacción del usuario',
                'PQRS',
            ],
            'code' => 'M-DP',
            'name' => 'Dispensación',
            'description' => 'Entrega de productos a usuarios junto a la información necesaria para su correcto uso.',
            'suppliers' => ['Usuarions, proceso de lmacenamiento'],
            'inputs' => ['Solicitudes de los usuarios, productos'],
            'procedures' => ['Por definir'],
            'outputs' => ['Productos dispensados'],
            'clients' => ['Usuarios, procesos de selección y adquisición'],
        ]);

        // 12
        Process::create([
            'process_type_id' => 3,
            'records' => [
                'Acta de devoluciones',
                'Acta de disposición final',
                ],
            'code' => 'M-DV',
            'name' => 'Devoluciones y Disposición Final',
            'description' => 'Devolución de productos a los proveedores y envío a destrucción de productos deteriorados, parcialmente consumidos, vencidos, fraudulentos, etc.',
            'suppliers' => ['Proceso de almacenamiento'],
            'inputs' => ['Productos objeto'],
            'procedures' => ['Por definir, elaboración de presupuestos, compras, contrataciones.'],
            'outputs' => ['Productos objeto'],
            'clients' => ['Proveedores'],
        ]);

        // 13
        Process::create([
            'process_type_id' => 3,
            'records' => [
                'Facturas',
                ],
            'code' => 'A-GR',
            'name' => 'Gestión de Recursos',
            'description' => 'Adquisiciones no farmacéuticas.',
            'suppliers' => ['Todos los procesos'],
            'inputs' => ['Requerimientos'],
            'procedures' => ['Análisis de requerimientos, elaboración de presupuestos, compras, contrataciones.'],
            'outputs' => ['Reparados, manutenciones'],
            'clients' => ['Todos los procesos'],
        ]);

    }
}
