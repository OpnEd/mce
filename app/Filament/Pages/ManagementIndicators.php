<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\View\View;

class ManagementIndicators extends Page
{
    protected static ?string $navigationGroup = '9. Sistema de Gestión de la Calidad';
    protected static ?string $navigationIcon = 'phosphor-presentation-chart';
    protected static ?string $navigationLabel = '9.16 / 9.20 - Indicadores de Gestión';

    protected static string $view = 'filament.pages.management-indicators';

    /* public function getHeader(): ?View
    {
        return view('filament.settings.header.indicadores-de-gestion', []);
    } */
}
