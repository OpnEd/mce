<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Clusters\InspectionSurveillanceControlAudit;
use App\Filament\Pages\BaseSectionPage;
use Filament\Pages\Page;
use App\Models\MinutesIvcSectionEntry;
use App\Services\LinkResolver;
use Filament\Facades\Filament;

class Selection extends BaseSectionPage
{
    protected static ?int $navigationSort = 31;
    protected static ?string $navigationLabel = '10. Selección';
    protected static ?string $navigationGroup = 'Secretaría de Salud';

    protected static string $view = 'filament.pages.tenancy.selection';
    protected static ?string $slug = 'seleccion';

    public string $section = ' Proceso de Selección';
}
