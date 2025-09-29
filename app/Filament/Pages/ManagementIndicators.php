<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\SaludPublica;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
use Filament\Actions\Action;

class ManagementIndicators extends Page
{

    //protected static ?string $cluster = SaludPublica::class;
    //protected static ?int $navigationSort = 24;
    //protected static ?string $navigationGroup = 'Secretaría de Salud';
    //protected static ?string $navigationLabel = '9.16 / 9.20- Indicadores de G.';

    protected static string $view = 'filament.pages.management-indicators';


    public function getHeading(): string
    {
        return __('pages.management_indicators');
    }

    public function getSubheading(): ?string
    {
        return __('En este sitio encuentras los indicadores de gestión de cada uno de los procesos misionales.');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make(__('pages.return_to_previous_page'))
                ->icon('phosphor-arrow-left')
                ->url(url()->previous() ?? route('filament.admin.pages.gestion-de-calidad')),
        ];
    }
}
