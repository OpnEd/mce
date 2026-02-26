<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Clusters\InspectionSurveillanceControlAudit;
use App\Filament\Pages\BaseSectionPage;
use Filament\Pages\Page;
use App\Models\MinutesIvcSectionEntry;
use App\Services\LinkResolver;
use Filament\Facades\Filament;

class Areas extends BaseSectionPage
{
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationLabel = '5. Áreas';
    protected static ?string $navigationGroup = 'Secretaría de Salud';

    protected static string $view = 'filament.pages.tenancy.areas';
    protected static ?string $slug = 'areas';
    
    public string $section = 'Áreas';
}
