<?php

namespace App\Services\Quality;

use App\Models\Quality\Training\Course;
use App\Models\Quality\Training\Enrollment;
use App\Repositories\Interfaces\CourseInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TrainingService
{
    public function __construct(
        protected CourseInterface $courses
    ) {}

    /**
     * Lista los cursos activos disponibles para el usuario.
     */
    public function listAvailableCourses(): Collection
    {
        return $this->courses->findActivecourses();
    }

    /**
     * Inscribe al usuario en un curso.
     *
     * @param int $userId
     * @param int $courseId
     * @return bool
     */

    public function enroll(int $teamId, int $userId, int $courseId): bool
    {
        // 1) Verifica inscripción existente
        if (Enrollment::where('user_id', $userId)->where('course_id', $courseId)->exists()) {
            return false;
        }

        // 2) Transacción: creas Enrollment y Progress
        DB::transaction(function () use ($teamId, $userId, $courseId) {
            $enrollment = Enrollment::create([
                'team_id'     => $teamId,
                'user_id'     => $userId,
                'course_id'   => $courseId,
                'started_at'  => now(),
                'status'      => 'in progress',
            ]);

            // Creas el progreso para cada lección
            $lessons = Course::findOrFail($courseId)->lessons()->get();
            foreach ($lessons as $lesson) {
                $enrollment->progress()->create([
                    'lesson_id' => $lesson->id,
                    'status'    => 'not started',
                ]);
            }
        });

        return true;
        
    }

    /**
     * Obtiene el progreso del usuario en un curso.
     *
     * @param int $userId
     * @param int $courseId
     * @return array
     */
    public function progress(int $userId, int $courseId): array
    {
        // Obtiene el total de módulos del curso
        $totalModules = DB::table('modules')
            ->where('course_id', $courseId)
            ->count();

        // Obtiene el número de módulos completados por el usuario
        $completedModules = DB::table('module_user')
            ->where('user_id', $userId)
            ->whereIn('module_id', function ($query) use ($courseId) {
                $query->select('id')
                    ->from('modules')
                    ->where('course_id', $courseId);
            })
            ->count();

        // Calcula el porcentaje de progreso
        $progress = $totalModules > 0 ? round(($completedModules / $totalModules) * 100, 2) : 0;

        return [
            'total_modules' => $totalModules,
            'completed_modules' => $completedModules,
            'progress_percent' => $progress,
        ];
    }

    /**
     * Otras operaciones (enroll, progreso, etc.)...
     */
}
