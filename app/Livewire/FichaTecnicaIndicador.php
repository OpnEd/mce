<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\IndicatorService;
use Filament\Facades\Filament;

class FichaTecnicaIndicador extends Component
{
    public $indicatorName;
    public $fichaTecnica;
    public $role;
    public $qualityGoal;
    public $teamId;

    protected $listeners = ['cargarFichaTecnica'];

    public function mount($indicador)
    {
        $this->teamId = Filament::getTenant()->id;
        $this->indicatorName = $indicador;
        $this->obtenerFichaTecnica();
    }

    public function obtenerFichaTecnica()
    {
        $servicio = new IndicatorService();
        $this->fichaTecnica = $servicio->getMonthlyCompliance($this->teamId, $this->indicatorName);

        // Asignar role y qualityGoal desde fichaTecnica
        if ($this->fichaTecnica) {
            $this->role = $this->fichaTecnica['roleName'];
            $this->qualityGoal = $this->fichaTecnica['qualityGoal'];
        }
    }

    public function render()
    {
        return view('livewire.ficha-tecnica-indicador');
    }
}
