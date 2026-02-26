<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Clusters\InspectionSurveillanceControlAudit;
use App\Filament\Pages\BaseSectionPage;
use App\Models\MinutesIvcSectionEntry;
use App\Services\LinkResolver;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class OtherAspects extends BaseSectionPage
{
    protected static ?int $navigationSort = 8;
    protected static ?string $navigationLabel = '8. Otros Aspectos';
    protected static ?string $navigationGroup = 'Secretaría de Salud';

    protected static string $view = 'filament.pages.tenancy.other-aspects';
    protected static ?string $slug = 'otros-aspectos';

    public string $section = 'Otros aspectos';
}
