<?php

namespace App\Filament\Pages\Quality;

use Filament\Pages\Page;

class CourseList extends Page
{
    protected static ?string $navigationGroup = '9. Sistema de Gestión de la Calidad';
    protected static ?string $navigationLabel = '9.12 / 9.13- Capacitación';

    protected static string $view = 'filament.pages.quality.course-list';
}
