<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Pages\BaseSectionPage;
use App\Models\MinutesIvcSectionEntry;
use App\Models\TenantSetting;
use App\Services\LinkResolver;
use App\Services\Tenancy\SettingsService;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class HumanResource extends BaseSectionPage
{
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.tenancy.human-resource';
    protected static ?string $navigationLabel = '2. Recurso Humano';
    protected static ?string $navigationGroup = 'Secretaría de Salud';
    protected static ?string $slug = 'recurso-humano';

    public string $section = 'Recurso Humano';
}
