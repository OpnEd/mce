<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Clusters\InspectionSurveillanceControlAudit;
use App\Filament\Pages\BaseSectionPage;
use Filament\Pages\Page;
use App\Models\MinutesIvcSectionEntry;
use App\Services\LinkResolver;
use Filament\Facades\Filament;

class ClassificationEstablishment extends BaseSectionPage
{
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationLabel = '6. Clasicación del Establecimiento';
    protected static ?string $navigationGroup = 'Secretaría de Salud';

    protected static string $view = 'filament.pages.tenancy.classification-establishment';
    protected static ?string $slug = 'clasificacion-del-establecimiento';
    
    public string $section = 'Clasificación del Establecimiento';
}
