<?php

namespace App\Filament\Pages\Quality;

use App\Models\TenantSetting;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;

class QualityPolicy extends Page
{
    protected static string $view = 'filament.pages.quality.quality-policy';
    protected static ?string $navigationGroup = '9. Sistema de Gestión de la Calidad';
    protected static ?string $navigationLabel = '9.8 - Política de Calidad';

    public $tenantSetting;
    public $qualityPolicy;
    public $team;
    public $teamId;
    public $customTitle = '9.8 - Política de Calidad';
    public $customSubTitle;
    public ?string $policyText = null;
    public array  $policyData = [];
    public array $headerLinks = [];
    public $customPageIcon = 'phosphor-file-arrow-up';

    public function mount()
    {
        $this->team = Filament::getTenant();
        $this->teamId = $this->team->id;
        $this->qualityPolicy = TenantSetting::where('team_id', $this->teamId)->where('setting_id', 3)->first();
        $this->customSubTitle = $this->team->getSettingValue(3);
        $this->policyData = $this->team->getSettingData(3) ?? [];
        $this->headerLinks = [
            [
                'label'  => '9.12 - Capacitación',
                'color'  => 'info',
                'icon'   => 'phosphor-rewind',
                'route'  => 'filament.admin.pages.training-commitment',
                'params' => [$this->teamId],
            ],
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
