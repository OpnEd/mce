<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Renombramos `progress` a `enrollment_lesson` y agregamos las columnas necesarias.
     */
    /* public function up(): void
    {
        // 1) Renombrar 'progress' a 'enrollment_lesson' solo si 'progress' existe y 'enrollment_lesson' no.
        if (Schema::hasTable('progress') && !Schema::hasTable('enrollment_lesson')) {
            Schema::rename('progress', 'enrollment_lesson');
        }

        // 2) Crear la tabla 'enrollment_lesson' solo si no existe.
        // Esto evita el error si la migración se ejecuta sobre una base de datos que ya tiene la tabla.
        if (!Schema::hasTable('enrollment_lesson')) {
            Schema::create('enrollment_lesson', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }

        // 3) Alter table: añadir columnas y claves foráneas, verificando si ya existen.
        Schema::table('enrollment_lesson', function (Blueprint $table) {
            // Relaciones
            if (!Schema::hasColumn('enrollment_lesson', 'enrollment_id')) {
                $table->foreignId('enrollment_id')
                      ->after('id')
                      ->constrained('enrollments')
                      ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('enrollment_lesson', 'lesson_id')) {
                $table->foreignId('lesson_id')
                      ->after('enrollment_id')
                      ->constrained('lessons')
                      ->cascadeOnDelete();
            }

            // Estado y marcas de tiempo específicas
            if (!Schema::hasColumn('enrollment_lesson', 'status')) {
                $table->enum('status', ['not_started', 'in_progress', 'completed'])
                      ->default('not_started')
                      ->after('lesson_id');
            }

            if (!Schema::hasColumn('enrollment_lesson', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('enrollment_lesson', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('started_at');
            }
            if (!Schema::hasColumn('enrollment_lesson', 'last_accessed_at')) {
                $table->timestamp('last_accessed_at')->nullable()->after('completed_at');
            }

            // Unicidad por enrollment+lesson para evitar duplicados
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('enrollment_lesson');
            if (!array_key_exists('enrollment_lesson_unique', $indexes)) {
                $table->unique(['enrollment_id', 'lesson_id'], 'enrollment_lesson_unique');
            }
        });
    } */

    /**
     * Reverse the migrations.
     *
     * Revertimos los cambios: eliminamos columnas y renombramos de vuelta a `progress`.
     * Ten en cuenta que esto eliminará las columnas nuevas y la constraint única.
     */
    public function down(): void
    {
        // Verificamos existencia
        if (! Schema::hasTable('enrollment_lesson')) {
            return;
        }
    
        Schema::table('enrollment_lesson', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('enrollment_lesson');
    
            if (array_key_exists('enrollment_lesson_unique', $indexes)) {
                $table->dropUnique('enrollment_lesson_unique');
            }
    
            // Eliminar columnas y sus claves foráneas si existen
            $columnsToDrop = [
                'last_accessed_at',
                'completed_at',
                'started_at',
                'status',
                'lesson_id',
                'enrollment_id',
            ];
    
            // Primero se eliminan las claves foráneas para evitar errores
            if (Schema::hasColumn('enrollment_lesson', 'lesson_id')) $table->dropForeign(['lesson_id']);
            if (Schema::hasColumn('enrollment_lesson', 'enrollment_id')) $table->dropForeign(['enrollment_id']);
    
            // Luego se eliminan las columnas
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('enrollment_lesson', $column)) $table->dropColumn($column);
            }
        });
    
        // Renombrar de vuelta a 'progress'
        Schema::rename('enrollment_lesson', 'progress');
    }
};
