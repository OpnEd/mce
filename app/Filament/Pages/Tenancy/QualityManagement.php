<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Pages\BaseSectionPage;
use App\Models\MinutesIvcSectionEntry;
use App\Services\LinkResolver;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class QualityManagement extends BaseSectionPage
{
    protected static string $view = 'filament.pages.tenancy.quality-management';
    protected static ?int $navigationSort = 9;
    protected static ?string $navigationLabel = '9. Sistema de Gestión de Calidad';
    protected static ?string $navigationGroup = 'Secretaría de Salud';
    protected static ?string $slug = 'gestion-de-calidad';

    public string $section = 'Sistema de gestión de calidad';
}
