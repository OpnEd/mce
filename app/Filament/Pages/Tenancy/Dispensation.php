<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Clusters\InspectionSurveillanceControlAudit;
use App\Filament\Pages\BaseSectionPage;
use Filament\Pages\Page;
use App\Models\MinutesIvcSectionEntry;
use App\Services\LinkResolver;
use Filament\Facades\Filament;

class Dispensation extends BaseSectionPage
{
    protected static ?int $navigationSort = 35;
    protected static ?string $navigationLabel = '14. Dispensación';
    protected static ?string $navigationGroup = 'Secretaría de Salud';

    protected static string $view = 'filament.pages.tenancy.dispensation';
    protected static ?string $slug = 'dispensacion';
    public string $section = ' Proceso de Dispensación';    // grouped settings ready for rendering;
}
