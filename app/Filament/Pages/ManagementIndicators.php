<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\SaludPublica;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;

class ManagementIndicators extends Page
{

    //protected static ?string $cluster = SaludPublica::class;
    protected static ?int $navigationSort = 24;
    protected static ?string $navigationGroup = 'Secretaría de Salud';
    protected static ?string $navigationLabel = '9.16 / 9.20- Indicadores de G.';

    protected static string $view = 'filament.pages.management-indicators';

    /* public function getHeader(): ?View
    {
        return view('filament.settings.header.indicadores-de-gestion', []);
    } */

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
