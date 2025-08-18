<?php

namespace Database\Seeders;

use App\Models\Quality\Training\Lesson;
use App\Models\Quality\Training\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Este seeder puebla las lecciones desde el archivo de configuración config/lessons_template.php
     * o config/lesson_template.php (acepta ambos nombres).
     * Asume que los módulos han sido creados previamente y tienen un atributo 'order'
     * que corresponde a las claves en la plantilla de lecciones.
     */
    public function run(): void
    {
        // Intentamos ambos nombres para mayor tolerancia
        $lessonTemplates = config('lessons_template', config('lesson_template', []));

        if (empty($lessonTemplates) || !is_array($lessonTemplates)) {
            $this->command->warn('El archivo de configuración lessons_template.php / lesson_template.php está vacío o no existe.');
            return;
        }

        $this->command->info('Iniciando LessonSeeder...');
        $createdLessonsCount = 0;
        $skippedLessonsCount = 0;
        $errors = [];

        DB::transaction(function () use ($lessonTemplates, &$createdLessonsCount, &$skippedLessonsCount, &$errors) {
            foreach ($lessonTemplates as $moduleOrder => $lessonsForModule) {
                // Encontrar el módulo por su orden
                $module = Module::where('order', $moduleOrder)->first();

                if (!$module) {
                    $this->command->warn("Módulo con orden #{$moduleOrder} no encontrado. Omitiendo sus lecciones.");
                    continue;
                }

                if (!is_array($lessonsForModule)) {
                    $this->command->warn("Plantilla para módulo {$moduleOrder} no es un array. Omitiendo.");
                    continue;
                }

                foreach ($lessonsForModule as $index => $lessonTemplate) {
                    try {
                        // Normalizar campos mínimos
                        $lessonTitle = $lessonTemplate['title'] ?? 'Lección sin título';
                        $lessonObjective = $lessonTemplate['objective'] ?? null;
                        $lessonDescription = $lessonTemplate['description'] ?? null;
                        $lessonDuration = isset($lessonTemplate['duration']) ? intval($lessonTemplate['duration']) : null;
                        $lessonOrder = $lessonTemplate['order'] ?? ($index + 1);
                        $lessonVideo = $lessonTemplate['video_url'] ?? null;
                        $lessonIframe = $lessonTemplate['iframe'] ?? null;
                        $rawContent = $lessonTemplate['content'] ?? null;

                        // Evitar duplicar lecciones por title en el mismo módulo
                        $lessonExists = Lesson::where('module_id', $module->id)
                                              ->where('title', $lessonTitle)
                                              ->exists();
                        if ($lessonExists) {
                            $skippedLessonsCount++;
                            continue;
                        }

                        // Content: si viene como string JSON, decodificar; si ya es array, usarlo; si es null, usar texto vacío
                        $content = null;
                        if (is_string($rawContent)) {
                            $decoded = json_decode($rawContent, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $content = $decoded;
                            } else {
                                // tratar como texto plano
                                $content = ['text' => $rawContent];
                            }
                        } elseif (is_array($rawContent)) {
                            $content = $rawContent;
                        } else {
                            $content = ['text' => '']; // o usa faker si quieres contenido por defecto
                        }

                        // Crear la lección
                        Lesson::create([
                            'title' => $lessonTitle,
                            'objective' => $lessonObjective,
                            'description' => $lessonDescription,
                            'duration' => $lessonDuration,
                            'module_id' => $module->id,
                            'order' => $lessonOrder,
                            'content' => $content, // modelo debe castear a JSON
                            'video_url' => $lessonVideo,
                            'iframe' => $lessonIframe,
                            'active' => true,
                            'created_at' => Carbon::now()->subMinutes(rand(0, 9000)),
                            'updated_at' => Carbon::now()->subMinutes(rand(0, 4000)),
                        ]);

                        $createdLessonsCount++;
                    } catch (\Throwable $e) {
                        // Registrar error pero continuar con las demás lecciones
                        $errors[] = "Error creando lección para módulo {$moduleOrder} (title: {$lessonTitle}): " . $e->getMessage();
                        $this->command->error("Error en lección módulo {$moduleOrder}: " . $e->getMessage());
                    }
                } // end foreach lessonsForModule
            } // end foreach modules
        }); // end transaction

        // Mensajes finales
        $this->command->info("LessonSeeder finalizado. Lecciones creadas: {$createdLessonsCount}. Lecciones omitidas (duplicadas): {$skippedLessonsCount}.");

        if (!empty($errors)) {
            $this->command->warn('Se encontraron errores parciales al crear algunas lecciones:');
            foreach ($errors as $err) {
                $this->command->warn(" - {$err}");
            }
        }
    }
}
