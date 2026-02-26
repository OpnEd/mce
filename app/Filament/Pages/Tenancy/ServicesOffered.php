<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Clusters\InspectionSurveillanceControlAudit;
use App\Filament\Pages\BaseSectionPage;
use Filament\Pages\Page;
use App\Models\MinutesIvcSectionEntry;
use App\Services\LinkResolver;
use Filament\Facades\Filament;

class ServicesOffered extends BaseSectionPage
{
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationLabel = '7. Servicios ofrecidos';
    protected static ?string $navigationGroup = 'Secretaría de Salud';

    protected static string $view = 'filament.pages.tenancy.services-offered';
    protected static ?string $slug = 'servicios-ofrecidos';

    public string $section = 'Servicios Ofrecidos';
}
