<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ManagementIndicatorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indicators = [
            //1.Selección
            [
                'quality_goal_id' => 1,
                'name' => 'Selección',
                'objective' => 'Vigilar incumplimientos en la disponibilidad por fallas en la selección.',
                'description' => '# de productos faltantes por considerarse de baja rotación o alto costo (productos no seleccionados), no se incluyen productos descontinuados, agotados en el mercado, o que por cualquier otra razón ajena a la responsabilidad de la droguería no se hallan disponibles.',
                'type' => 'Cardinal',
                'information_source' => 'Registros de faltantes.',
                'numerator' => '# de faltantes de baja rotación',
                'denominator' => null,
                'denominator_description' => 'NULL',
                'created_at' => '2025-07-16 21:51:04',
                'updated_at' => '2025-07-16 21:51:04',
            ],
            //2.Adquisición
            [
                'quality_goal_id' => 1,
                'name' => 'Adquisición',
                'objective' => 'Vigilar incumplimientos en la disponibilidad debidos a fallas en el proceso de adquisición',
                'description' => '# de productos faltantes por fallas en el proceso de adquisición. Productos de alta rotación que el usuario no encuentra.',
                'type' => 'Cardinal',
                'information_source' => 'Registros de faltantes.',
                'numerator' => '# de faltantes de alta rotación',
                'denominator' => null,
                'denominator_description' => null,
                'created_at' => '2025-07-16 21:51:04',
                'updated_at' => '2025-07-16 21:51:04',
            ],
            //3.Recepción
            [
                'quality_goal_id' => 2,
                'name' => 'Recepción',
                'objective' => 'Vigilar la realización de recepción técnica de todos los productos que ingresan al establecimiento',
                'description' => 'Proporción de envíos por parte de los proveedores a los que se les realiza la recepción técnica.',
                'type' => 'Porcentual',
                'information_source' => 'Registro de órdenes de Compra y registro de recepción técnica',
                'numerator' => '# de recepciones técnicas',
                'denominator' => null,
                'denominator_description' => '# de órdenes de compra',
                'created_at' => '2025-07-16 21:51:04',
                'updated_at' => '2025-07-16 21:51:04',
            ],
            //4.Almacenamiento - VAMT
            [
                'quality_goal_id' => 2,
                'name' => 'Almacenamiento - VAMT',
                'objective' => 'Vigilar el monitoreo diario de variables ambientales',
                'description' => 'Variables Ambientales Medidas a Tiempo - Cumplimiento con la obligación de verificar, como mínimo, dos veces al día -mañana y tarde-noche-, que la temperatura y la humedad se encuentren dentro de los rangos permitidos.',
                'type' => 'Cardinal',
                'information_source' => 'Registros de temperatura y humedad',
                'numerator' => 'sumatoria de registros diarios en la mañana y de registros diarios en la tarde-noche',
                'denominator' => 30,
                'denominator_description' => '# de días en el mes',
                'created_at' => '2025-07-16 21:51:04',
                'updated_at' => '2025-07-16 21:51:04',
            ],
            //5.Almacenamiento - VADR
            [
                'quality_goal_id' => 2,
                'name' => 'Almacenamiento - VADR',
                'objective' => 'Vigilar la permanencia de variables ambientales dentro de rangos permitidos',
                'description' => 'Variables Ambientales Dentro de Rango - Se calcula la proporción del tiempo que las variables ambientales se encuentran dentro de los rangos permitidos.',
                'type' => 'Porcentual',
                'information_source' => 'Registros de temperatura y humedad',
                'numerator' => '# de registros que indican desviación',
                'denominator' => null,
                'denominator_description' => '# total de registros',
                'created_at' => '2025-07-16 21:51:04',
                'updated_at' => '2025-07-16 21:51:04',
            ],
            //6.Devoluciones
            [
                'quality_goal_id' => 6,
                'name' => 'Devoluciones',
                'objective' => 'Vigilar devoluciones por fallas en los procesos de la droguería -vencimiento y deterioro de productos durante el almacenamiento',
                'description' => '# de productos que es necesario devolver al proveedor, o descartarlos, debido al acercamiento o cumplimiento de la fecha de vencimiento, o por deterioro atribuible a malas prácticas de manejo o almacenamiento dentro de nuestro establecimiento.',
                'type' => 'Cardinal',
                'information_source' => 'Registro de devoluciones y descartes.',
                'numerator' => '# de productos devueltos o descartados',
                'denominator' => null,
                'denominator_description' => null,
                'created_at' => '2025-07-16 21:51:04',
                'updated_at' => '2025-07-16 21:51:04',
            ],
            //7.Dispensación - PUR
            [
                'quality_goal_id' => 5,
                'name' => 'Dispensación - PUR',
                'objective' => 'Vigilar que se realize la promoción del uso racional de los medicamentos priorizados (venta con fórmula médica)',
                'description' => 'Promoción del Uso Racional - Frecuencia con que se brinda información sobre el uso racional de medicamentos',
                'type' => 'Cardinal',
                'information_source' => 'Registros de promoción del uso adecuado de medicamentos',
                'numerator' => '# de actividades de promoción del uso racional documentadas',
                'denominator' => null,
                'denominator_description' => null,
                'created_at' => '2025-07-16 21:51:04',
                'updated_at' => '2025-07-16 21:51:04',
            ],
            //8.Dispensación - SU
            [
                'quality_goal_id' => 5,
                'name' => 'Dispensación - SU',
                'objective' => 'Vigilar que los usuarios se sientan atendidos con cordialidad, humanidad, efectividad y con productos seguros a precios razonables',
                'description' => 'Satisfacción del Usuario - Encuesta de satisfacción a los usuarios que evalúa distintas áreas y aspectos del establecimiento',
                'type' => 'Cardinal',
                'information_source' => 'Encuestas de satisfacción del usuario',
                'numerator' => 'puntaje promedio en la pregunta de satisfacción en general',
                'denominator' => null,
                'denominator_description' => null,
                'created_at' => '2025-07-16 21:51:04',
                'updated_at' => '2025-07-16 21:51:04',
            ],
        ];

        // Deshabilitar foreign key checks temporalmente (por la FK quality_goal_id)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Limpiar tabla y resetear autoincrement
        DB::table('management_indicators')->truncate();
        
        // Insertar todos los registros de una vez (bulk insert eficiente)
        DB::table('management_indicators')->insert($indicators);
        
        // Reactivar foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

        
        /* foreach ($indicators as $item) {

            ManagementIndicator::create([
                'quality_goal_id'        => $item['quality_goal_id'],
                'name'                   => $item['name'],
                'objective'              => $item['objective'],
                'description'            => $item['description'],
                'type'                   => $item['type'],
                'information_source'     => $item['information_source'],
                'numerator'              => $item['numerator'],
                'denominator_description' => $item['denominator_description'],
                'denominator'            => $item['denominator'],
            ]);
        } */
                /*[
                 [
                                'quality_goal_id' => 1,
                                'name' => 'Disponibilidad',
                                'objective' => 'Medir el desempeño de los procesos de selección y adquisición. Contribuir a mantener el stock de productos necesario para cumplir con las expectativas de la comunidad.',
                                'description' => '# de faltantes en el mes (Medicamentos que no podemos dispensar debido a que no contamos con existencias, a pesar de estar seleccionados. No se incluyen por lo tanto en este indicador medicamentos no seleccionados, agotados en el mercado, descontinuados o cualquier otra razón ajena a la responsabilidad de la droguería.)',
                                'type' => 'Cardinal',
                                'information_source' => 'Registros de faltantes.',
                                'numerator' => '# de faltantes en el mes',
                                'denominator_description' => null,
                                'denominator' => null,
                            ],
                
                            // 2
                            [
                                'quality_goal_id' => 2,
                                'name' => 'Calidad de producto',
                                'objective' => 'Medir el desempeño del proceso de almacenamiento.',
                                'description' => 'Número de medicamentos con problemas de calidad debido a fallas en los procesos de la droguería. No se incluyen medicamentos que tienen problemas de calidad desde antes de ingresar a la droguería.',
                                'type' => 'Cardinal',
                                'information_source' => 'PQRS, encuestas de satisfacción, inspección de productos (lo que incluye recepciones técnicas y verificaciones aleatorias de registros sanitarios en las fuentes del INVIMA).',
                                'numerator' => '# de medicamentos con problemas de calidad',
                                'denominator_description' => null,
                                'denominator' => null,
                            ],
                
                            // 3 ***
                            [
                                'quality_goal_id' => 4,
                                'name' => 'Cumplimiento Normativo',
                                'objective' => 'Medir el grado en que se cumple con los requerimientos normativos.',
                                'description' => 'Proporción de aspectos auditados que cumplen en relación con el número total de aspectos programados para auditoría.',
                                'type' => 'Porcentual',
                                'information_source' => 'Informes de autoinspecciones, Actas de visita de la Secretaría de Salud',
                                'numerator' => '# de aspectos evaluados que cumplen * 100',
                                'denominator_description' => '# total de aspectos evaluados',
                                'denominator' => 95,
                            ],
                
                            // 4 ***
                            [
                                'quality_goal_id' => 5,
                                'name' => 'Satisfacción de los usuarios',
                                'objective' => 'Medir el grado en que los usuarios están satisfechos con la atención que se les brinda (amabilidad y rapidez), la calidad y la disponibilidad de productos, la presentación del establecimiento, etc. Detectar los aspectos del servicio, los productos o el esablecimiento que se deben mejorar.',
                                'description' => 'Proporción de los distintos niveles de calificaciones dadas a cada pregunta. Ejemplo: (# de respuestas Exelente a la pregunta 1 * 100 / # total de respuestas a la pregunta 1)',
                                'type' => 'Porcentual',
                                'information_source' => 'Encuestas de satisfacción del usuario',
                                'numerator' => '(# de respuestas / nivel / pregunta) * 100',
                                'denominator_description' => '# total de respuestas / pregunta',
                                'denominator' => 240,
                            ],
                
                            // 5
                            [
                                'quality_goal_id' => 6,
                                'name' => 'Devoluciones',
                                'objective' => 'Medir el desempeño de los procesos de adquisición y almacenamiento. Contribuir al mantenimiento de un inventario óptimo.',
                                'description' => 'Ocasiones en que es necesario devolver productos al proveedor, o descartarlos, debido al acercamiento o cumplimiento de la fecha de vencimiento, o por deterioro atribuible a malas prácticas de manejo o almacenamiento.',
                                'type' => 'Cardinal',
                                'information_source' => 'Registro de devoluciones y descartes.',
                                'numerator' => '# de productos devueltos o descartados',
                                'denominator_description' => null,
                                'denominator' => null,
                            ],
                
                            // 6
                            [
                                'quality_goal_id' => 2,
                                'name' => 'Monitoreo ambiental diario',
                                'objective' => 'Verificar que se están monitorizando las variables ambientales.',
                                'description' => 'Cumplimiento con la oblicación de verificar, como mínimo, tantas veces al día como se establece en la meta, que la temperatura y la humedad se encuentren dentro de los rangos permitidos.',
                                'type' => 'Cardinal',
                                'information_source' => 'Registros de temeratura y humedad',
                                'numerator' => '# de registros realizados al día',
                                'denominator_description' => null,
                                'denominator' => null,
                            ],
                
                            // 7
                            [
                                'quality_goal_id' => 2,
                                'name' => 'Monitoreo ambiental mensual',
                                'objective' => 'Verificar que se están monitorizando las variables ambientales.',
                                'description' => 'Grado en el que se cumple con la obligación de verificar en dos ocasiones al día, mañana, y tarde-noche, que la temperatura y la humedad se encuentren dentro de los rangos permitidos.',
                                'type' => 'Porcentual',
                                'information_source' => 'Registros de temeratura y humedad',
                                'numerator' => '# de registros realizados (desde el primer día del mes hasta la fecha actual) * 100',
                                'denominator_description' => '# de registros programados (meta de monitoreo ambiental diario * # de días transcurridos desde el primer día del mes hasta la fecha actual )',
                                'denominator' => null,
                            ],
                
                            // 8
                            [
                                'quality_goal_id' => 7,
                                'name' => 'Capacitaciones',
                                'objective' => 'Medir el desempeño del proceso de Inducción y capacitación.',
                                'description' => 'Cumplimiento de cronograma de capacitaciones',
                                'type' => 'Porcentual',
                                'information_source' => 'Actas de capacitación, cronograma',
                                'numerator' => '# de capacitaciones realizadas * 100',
                                'denominator_description' => '# de capacitaciones programadas',
                                'denominator' => null,
                            ],
                
                            // 9
                            [
                                'quality_goal_id' => 8,
                                'name' => 'Mejora continua',
                                'objective' => 'Medir el desempeño de los procesos de evaluación y mejora continua.',
                                'description' => 'Proporción de planes de acción ejecutados dentro del plazo establecido.',
                                'type' => 'Porcentual',
                                'information_source' => 'Registro de planes de acción',
                                'numerator' => '# de planes de acción al día o ejecutados dentro del plazo * 100',
                                'denominator_description' => '# total de planes de acción vigentes',
                                'denominator' => null,
                            ],
                
                            // 10 - Actualizado 28/10/2024
                            [
                                'quality_goal_id' => 9,
                                'name' => 'Promoción del uso racional de medicamentos',
                                'objective' => 'Medir el desempeño del proceso de dispensación.',
                                'description' => 'Frecuencia con que se brinda información sobre el uso adecuado de medicamentos',
                                'type' => 'Cardinal',
                                'information_source' => 'Registro de promoción del uso racional',
                                'numerator' => '# de ocasiones en que se brinda al usuario información sobre el uso racional de los medicamentos',
                                'denominator_description' => null,
                                'denominator' => null,
                            ],
                
                            // 11
                            [
                                'quality_goal_id' => 9,
                                'name' => 'Errores de dispensación',
                                'objective' => 'Medir el desempeño del proceso de dispensación.',
                                'description' => 'Número de ocasiones en que se cometen errores en la dispensación de los medicamentos.',
                                'type' => 'Cardinal',
                                'information_source' => 'Registro de errores de dispensación',
                                'numerator' => '# de errores de dispensación',
                                'denominator_description' => null,
                                'denominator' => null,
                            ],
                
                            // 12 Actualizado 28/10/2024
                            [
                                'quality_goal_id' => 2,
                                'name' => 'Recepción técnica',
                                'objective' => 'Medir el desempeño del proceso de recepción técnica y administrativa.',
                                'description' => 'Proporción de envíos por parte de los proveedores a los que se les realiza la recepción técnica.',
                                'type' => 'Porcentual',
                                'information_source' => 'Órdenes de compra, Registro de recepciones técnicas.',
                                'numerator' => '# de recepciones técnicas * 100',
                                'denominator_description' => '# de Órdenes de compra',
                                'denominator' => null,
                            ],
                
                            // 13 ***
                            [
                                'quality_goal_id' => 3,
                                'name' => 'Limpieza y sanitización - Almacenamiento',
                                'objective' => 'Verificar que los productos se estén almacenando en áras con las condiciones de higiene necesarias para la conservación de la calidad.',
                                'description' => 'Recuento mensual de actividades de limpieza y sanitización en el área de almacenamiento.',
                                'type' => 'Porcentual',
                                'information_source' => 'Registro de actividades de limpieza y sanitización',
                                'numerator' => '# de actividades de limpieza y sanitización efectuadas * 100',
                                'denominator_description' => '# total de actividades programadas',
                                'denominator' => 16,
                            ],
                
                            // 14 ***
                            [
                                'quality_goal_id' => 3,
                                'name' => 'Limpieza y sanitización - Inyectología',
                                'objective' => 'Verificar que se estén desarrollando las actividades de limpieza y sanitización con la frecuencia necesaria para brindar un servicio de inyectología seguro.',
                                'description' => 'Recuento mensual de actividades de limpieza y sanitización en el área de inyectología.',
                                'type' => 'Porcentual',
                                'information_source' => 'Registro de actividades de limpieza y sanitización',
                                'numerator' => '# de actividades de limpieza y sanitización efectuadas * 100',
                                'denominator_description' => '# total de actividades programadas',
                                'denominator' => 16,
                            ],
                
                            // 15
                            [
                                'quality_goal_id' => 4,
                                'name' => 'Autoinspecciones',
                                'objective' => 'Medir el desempeño de los procesos de evaluación y seguimiento.',
                                'description' => 'Cumplimiento del cronograma de autoinspecciones: número de autoinspecciones realizadas en relación con las programadas.',
                                'type' => 'Porcentual',
                                'information_source' => 'Registro de autoinspecciones',
                                'numerator' => '# de autoinspecciones efectuadas * 100',
                                'denominator_description' => '# total de autoinspecciones programadas',
                                'denominator' => null,
                            ],
                
                            // 16
                            [
                                'quality_goal_id' => 3,
                                'name' => 'Mantenimiento de equipos',
                                'objective' => 'Verificar que se dearrollan las actividades necesarias para mantener equipos en buen estado.',
                                'description' => 'Cumplimiento del cronograma de mantenimiento de equipos: número de mantenimientos de instalaciones realizados en relación con los programados.',
                                'type' => 'Porcentual',
                                'information_source' => 'Certificados de mantenimiento de equipos',
                                'numerator' => '# de mantenimientos efectuados * 100',
                                'denominator_description' => '# total de mantenimientos programados',
                                'denominator' => null,
                            ],
                
                            // 17
                            [
                                'quality_goal_id' => 3,
                                'name' => 'Mantenimiento de instalaciones y enseres',
                                'objective' => 'Verificar que se dearrollan las actividades necesarias para mantener instalaciones en buen estado.',
                                'description' => 'Cumplimiento del cronograma de mantenimiento de instalaciones y enseres, es decir, número de mantenimientos de instalaciones y enseres realizados en relación con los programados.',
                                'type' => 'Porcentual',
                                'information_source' => 'Certificados de mantenimiento de instalaciones y enseres',
                                'numerator' => '# de mantenimientos efectuados * 100',
                                'denominator_description' => '# total de mantenimientos programados',
                                'denominator' => null,
                            ],
                ]*/