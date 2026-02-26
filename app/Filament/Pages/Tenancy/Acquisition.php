<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Clusters\InspectionSurveillanceControlAudit;
use App\Filament\Pages\BaseSectionPage;
use App\Models\MinutesIvcSectionEntry;
use App\Services\LinkResolver;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class Acquisition extends BaseSectionPage
{
    protected static ?int $navigationSort = 32;
    protected static ?string $navigationLabel = '11. Adquisición';
    protected static ?string $navigationGroup = 'Secretaría de Salud';

    protected static string $view = 'filament.pages.tenancy.acquisition';
    protected static ?string $slug = 'adquisicion';
    
    public string $section = ' Proceso de Adquisición';
}
