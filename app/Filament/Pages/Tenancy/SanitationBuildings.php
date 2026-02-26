<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Clusters\InspectionSurveillanceControlAudit;
use App\Filament\Pages\BaseSectionPage;
use App\Models\MinutesIvcSectionEntry;
use App\Services\LinkResolver;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class SanitationBuildings extends BaseSectionPage
{
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = '4. Saneamiento de Edificaciones';
    protected static ?string $navigationGroup = 'Secretaría de Salud';

    protected static string $view = 'filament.pages.tenancy.sanitation-buildings';
    
    protected static ?string $slug = 'saneamiento-de-edificaciones';
    
    public string $section = 'Saneamiento de edificaciones';
}
