<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Clusters\InspectionSurveillanceControlAudit;
use App\Filament\Pages\BaseSectionPage;
use Filament\Pages\Page;
use App\Models\MinutesIvcSectionEntry;
use App\Services\LinkResolver;
use Filament\Facades\Filament;

class Returns extends BaseSectionPage
{
    protected static ?int $navigationSort = 36;
    protected static ?string $navigationLabel = '15. Devoluciones';
    protected static ?string $navigationGroup = 'Secretaría de Salud';

    protected static string $view = 'filament.pages.tenancy.returns';
    protected static ?string $slug = 'devoluciones';
    
    public string $section = ' Proceso de Devoluciones';
}
