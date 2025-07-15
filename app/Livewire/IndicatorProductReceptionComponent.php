<?php

namespace App\Livewire;

use App\Services\IndicatorService;
use Filament\Facades\Filament;
use Livewire\Component;

class IndicatorProductReceptionComponent extends Component
{
    public $data; // colección con ['month','percentage']
    public $teamId;

    public function mount(IndicatorService $indicatorService, $indicator)
    {
        $this->teamId = Filament::getTenant()->id;
        $this->data = $indicatorService->getMonthlyReceptionCompliance($this->teamId, $indicator);
    }
    public function render()
    {
        return view('livewire.indicator-product-reception-component');
    }
}
