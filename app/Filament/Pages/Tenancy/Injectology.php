<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Clusters\InspectionSurveillanceControlAudit;
use App\Filament\Pages\BaseSectionPage;
use Filament\Pages\Page;
use App\Models\MinutesIvcSectionEntry;
use App\Services\LinkResolver;
use Filament\Facades\Filament;

class Injectology extends BaseSectionPage
{
    //protected static ?string $cluster = InspectionSurveillanceControlAudit::class;

    protected static ?int $navigationSort = 39;
    protected static ?string $navigationLabel = 'Inyectología';
    protected static ?string $navigationGroup = 'Secretaría de Salud';

    protected static string $view = 'filament.pages.tenancy.injectology';
    protected static ?string $slug = 'inyectologia';
    
    public string $section = 'Inyectología';
}
