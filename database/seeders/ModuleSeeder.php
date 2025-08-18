<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Carbon\Carbon;
use App\Models\Quality\Training\Module;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Este seeder puebla solamente los módulos para el curso
     * nombrado "¿Cómo pasar la visita de la Secretaría de Salud?"
     * usando config/modules_template.php.
     */
    public function run(): void
    {
        $faker = Faker::create('es_ES'); // español
        $now = Carbon::now();

        $createdModulesCount = 0;
        $skippedModulesCount = 0;
        $errors = [];

        // Nombre exacto del curso objetivo
        $targetCourseName = '¿Cómo pasar la visita de la Secretaría de Salud?';

        // Cargar plantillas
        $moduleTemplates = config('modules_template', []);

        if (empty($moduleTemplates) || !is_array($moduleTemplates)) {
            $this->command->warn('La configuración modules_template está vacía o no existe en config/modules_template.php');
            return;
        }

        // Buscar el course por title o name (por si la columna se llama diferente)
        $courseRow = DB::table('courses')
            ->where('title', $targetCourseName)
            ->first();

        if (! $courseRow) {
            $this->command->warn("No se encontró ningún course con el nombre exacto: \"{$targetCourseName}\". Revisa la tabla courses.");
            return;
        }

        $courseId = $courseRow->id;

        DB::transaction(function () use ($faker, $now, &$createdModulesCount, &$skippedModulesCount, &$errors, $moduleTemplates, $courseId) {
            foreach ($moduleTemplates as $index => $template) {
                try {
                    // Normalizar campos mínimos de la plantilla
                    $moduleTitle = trim($template['title'] ?? ('Módulo ' . ($template['order'] ?? ($index + 1))));
                    $moduleObjective = $template['objective'] ?? $faker->sentence(rand(8, 16));
                    $moduleDescription = $template['description'] ?? $faker->paragraphs(rand(1, 3), true);
                    $moduleOrder = $template['order'] ?? ($index + 1);
                    $moduleDuration = $template['duration'] ?? rand(20, 180); // duración en minutos
                    $moduleImage = $template['image'] ?? null;

                    // Evitar crear duplicados por título en el mismo course
                    $exists = Module::where('course_id', $courseId)
                                    ->where('title', $moduleTitle)
                                    ->exists();

                    if ($exists) {
                        $skippedModulesCount++;
                        continue;
                    }

                    // Crear módulo
                    Module::create([
                        'title' => $moduleTitle,
                        'objective' => $moduleObjective,
                        'description' => $moduleDescription,
                        'duration' => $moduleDuration,
                        'course_id' => $courseId,
                        'order' => $moduleOrder,
                        'image' => $moduleImage,
                        'active' => true,
                        'created_at' => $now->copy()->subMinutes(rand(0, 10000)),
                        'updated_at' => $now->copy()->subMinutes(rand(0, 5000)),
                    ]);

                    $createdModulesCount++;
                } catch (\Throwable $e) {
                    $errors[] = "Error creando módulo (template index {$index}, title: {$moduleTitle}): " . $e->getMessage();
                    $this->command->error("Error creando módulo index {$index}: " . $e->getMessage());
                    // continuar con siguientes templates
                }
            }
        });

        // Mensajes finales
        $this->command->info("ModuleSeeder finalizado para course_id {$courseId}. Módulos creados: {$createdModulesCount}. Módulos omitidos (duplicados): {$skippedModulesCount}.");

        if (!empty($errors)) {
            $this->command->warn('Se encontraron errores al crear algunos módulos:');
            foreach ($errors as $err) {
                $this->command->warn(" - {$err}");
            }
        }
    }
}
