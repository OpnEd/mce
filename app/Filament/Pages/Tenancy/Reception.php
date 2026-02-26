<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Clusters\InspectionSurveillanceControlAudit;
use App\Filament\Pages\BaseSectionPage;
use Filament\Pages\Page;
use App\Models\MinutesIvcSectionEntry;
use App\Services\LinkResolver;
use Filament\Facades\Filament;

class Reception extends BaseSectionPage
{
    protected static ?int $navigationSort = 33;
    protected static ?string $navigationLabel = '12. Recepción';
    protected static ?string $navigationGroup = 'Secretaría de Salud';

    protected static string $view = 'filament.pages.tenancy.reception';
    protected static ?string $slug = 'recepcion';
    public string $section = ' Proceso de Recepción';
}
