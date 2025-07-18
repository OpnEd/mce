<?php

namespace App\Livewire;

use App\Models\MinutesIvcSection;
use App\Services\MinutesIvcService;
use Filament\Facades\Filament;
use Livewire\Component;

class MinutesIvcSectionComponent extends Component
{
    public $sections;
    public $teamId;

    protected $service;

    public function mount(MinutesIvcService $service)
    {
        $tenant = Filament::getTenant();
        
        $this->teamId = $tenant->id;
        
        // Llamada al servicio
        $this->service = $service;
        $this->sections = $this->service->getSectionsByTeam($this->teamId);
    }

    public function render()
    {
        return view('livewire.minutes-ivc-section-component', [
            'tenant' => $this->teamId,
            'sections' => $this->sections,
        ]);
    }
}
