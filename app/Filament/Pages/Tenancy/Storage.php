<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Clusters\InspectionSurveillanceControlAudit;
use App\Filament\Pages\BaseSectionPage;
use Filament\Pages\Page;
use App\Models\MinutesIvcSectionEntry;
use App\Services\LinkResolver;
use Filament\Facades\Filament;

class Storage extends BaseSectionPage
{
    protected static ?int $navigationSort = 34;
    protected static ?string $navigationLabel = '13. Almacenamiento';
    protected static ?string $navigationGroup = 'Secretaría de Salud';

    protected static string $view = 'filament.pages.tenancy.storage';
    protected static ?string $slug = 'almacenamiento';

    public string $section = ' Proceso de Almacenamiento';
}
