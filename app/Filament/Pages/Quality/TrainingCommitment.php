<?php

namespace App\Filament\Pages\Quality;

use App\Models\TenantSetting;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;

class TrainingCommitment extends Page
{
    protected static string $view = 'filament.pages.quality.training-commitment';

    protected static ?string $navigationGroup = '9. Sistema de Gestión de la Calidad';
    protected static ?string $navigationLabel = '9.12 - Capacitación';

    public $tenantSetting;
    public $qualityPolicy;
    public $teamId;
    public $customTitle = '9.12 - Capacitación del Personal';
    public $customSubTitle = 'Promovemos la capacitación del personal dentro de la política de calidad. Contamos con un calendario y listas de eventos como mecanismo para la programación de capacitaciones, e igualmente con un procedimiento que describe todo en materia de inducción, reinducción y capacitación del personal.';
    public $customPageIcon = 'heroicon-o-academic-cap';

    /**
     * Cada elemento describe un enlace:
     *  - label: texto visible
     *  - color: color de Filament (info, success, etc.)
     *  - icon: icono de Phosphor
     *  - route: nombre de la ruta
     *  - params: (opcional) array de parámetros para route()
     */
    public array $headerLinks = [];

    public function mount()
    {
        $this->teamId = Filament::getTenant()->id;
        $this->qualityPolicy = TenantSetting::where('team_id', $this->teamId)->where('setting_id', 3)->first();
        $this->headerLinks = [
            [
                'label'  => 'Política de Calidad',
                'color'  => 'info',
                'icon'   => 'phosphor-file-text',
                'route'  => 'filament.admin.pages.quality-policy',
                'params' => [$this->teamId],
            ],
            /* [
                'label'  => 'Calendario',
                'color'  => 'success',
                'icon'   => 'phosphor-calendar-check',
                'route'  => 'filament.admin.pages.events',
                'params' => [$this->teamId],
            ],
            [
                'label'  => 'Procedimiento de Inducción y Capacitación',
                'color'  => 'warning',
                'icon'   => 'phosphor-student',
                'route'  => 'document.details',
                'params' => [
                    $this->teamId,
                    'procedimiento-induccion-capacitacion'
                ],
            ], */
        ];
    }

    public function getHeader(): ?View
    {
        return view('filament.pages.quality.header', [
            'customTitle' => $this->customTitle,
            'customSubtitle' => $this->customSubTitle,
            'teamId' => $this->teamId,
            'headerLinks' => $this->headerLinks,
            'customPageIcon' => $this->customPageIcon,
        ]);
    }
}
