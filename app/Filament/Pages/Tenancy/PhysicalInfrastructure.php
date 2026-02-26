<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Clusters\InspectionSurveillanceControlAudit;
use App\Filament\Pages\BaseSectionPage;
use App\Models\TenantSetting;
use App\Services\Tenancy\SettingsService;
use Filament\Pages\Page;
use App\Models\MinutesIvcSectionEntry;
use App\Services\LinkResolver;
use Filament\Facades\Filament;

class PhysicalInfrastructure extends BaseSectionPage
{
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = '3. Infraestructura Física';
    protected static ?string $navigationGroup = 'Secretaría de Salud';

    protected static string $view = 'filament.pages.tenancy.physical-infrastructure';
    protected static ?string $slug = 'infraestructura-fisica';

    public string $section = 'Infraestructura Física';
}
