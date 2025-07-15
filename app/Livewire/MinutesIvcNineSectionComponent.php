<?php

namespace App\Livewire;

use App\Services\MinutesIvcService;
use Filament\Facades\Filament;
use Livewire\Component;

class MinutesIvcNineSectionComponent extends Component
{
    public $order; 
    public $section;
    public $entries;
    public $teamId;

    protected $service;

    /**
     * Ahora Livewire intentará inyectar $order desde la invocación del componente.
     */
    public function mount(MinutesIvcService $service, $order)
    {
        // 1) Obtener la sección de ese orden
        $this->service = $service;
        $this->order = $order;
        $this->teamId = Filament::getTenant()->id;
        $this->section = $this->service->getSectionByOrder($this->teamId, $this->order);

        // 2) Cargar sus entradas
        $this->entries = $this->section
            ? $this->service->getEntriesBySection($this->section->id)
            : collect();
    }

    public function render()
    {
        return view('livewire.minutes-ivc-nine-section-component', [
            'section' => $this->section,
            'entries' => $this->entries,
        ]);
    }
}
