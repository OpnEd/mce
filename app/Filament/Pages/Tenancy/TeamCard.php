<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Pages\BaseSectionPage;
use App\Models\MinutesIvcSectionEntry;
use App\Models\TenantSetting;
use Filament\Pages\Page;
use App\Services\Tenancy\SettingsService;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Services\LinkResolver;

class TeamCard extends BaseSectionPage
{
    use InteractsWithForms;

    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.tenancy.team-card';
    protected static ?string $navigationLabel = '1. Cédula del Establecimiento';
    protected static ?string $navigationGroup = 'Secretaría de Salud';
    protected static ?string $slug = 'cedula-del-establecimiento';
    
    public string $section = 'Cédula del establecimiento';
}
