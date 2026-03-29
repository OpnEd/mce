<?php

namespace App\Helpers\Training;

use App\Models\Quality\Training\Course;
use App\Models\Quality\Training\Enrollment;
use App\Models\Quality\Training\Lesson;
use App\Models\Quality\Training\Module;

class BreadcrumbHelper
{
    /**
     * Generar breadcrumbs para navegación en flujo de capacitación.
     *
     * @param Enrollment $enrollment
     * @param Module|null $module
     * @param Lesson|null $lesson
     * @return array<array{label: string, url: ?string}>
     */
    public static function getTrainingBreadcrumbs(
        Enrollment $enrollment,
        ?Module $module = null,
        ?Lesson $lesson = null
    ): array {
        $breadcrumbs = [];

        // Home
        $breadcrumbs[] = [
            'label' => 'Inicio',
            'url' => route('filament.app.pages.dashboard'),
        ];

        // Universidad / Mis Cursos
        $breadcrumbs[] = [
            'label' => 'Mis Cursos',
            'url' => route('filament.app.pages.student-dashboard'),
        ];

        // Curso actual
        $breadcrumbs[] = [
            'label' => $enrollment->course->title,
            'url' => route('filament.app.resources.enrollment-resource.view', ['record' => $enrollment->id]),
        ];

        // Módulo (si aplica)
        if ($module) {
            $breadcrumbs[] = [
                'label' => $module->title,
                'url' => null, // Sin ruta, solo información
            ];
        }

        // Lección (si aplica)
        if ($lesson) {
            $breadcrumbs[] = [
                'label' => $lesson->title,
                'url' => null, // Sin ruta, es la página actual
            ];
        }

        return $breadcrumbs;
    }

    /**
     * Generar solo los títulos para mostrar en página.
     */
    public static function getBreadcrumbPath(
        Enrollment $enrollment,
        ?Module $module = null,
        ?Lesson $lesson = null
    ): string {
        $parts = [$enrollment->course->title];

        if ($module) {
            $parts[] = $module->title;
        }

        if ($lesson) {
            $parts[] = $lesson->title;
        }

        return implode(' > ', $parts);
    }
}
