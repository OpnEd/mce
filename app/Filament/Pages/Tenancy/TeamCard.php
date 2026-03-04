<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Pages\BaseSectionPage;

class TeamCard extends BaseSectionPage
{
    public const NAVIGATION_SORT = 1;
    public const NAVIGATION_LABEL = '1. Cédula del Establecimiento';
    public const SLUG = 'cedula-del-establecimiento';
    public const VIEW = 'filament.pages.tenancy.team-card';
    public const SECTION = 'Cédula del establecimiento';
}
