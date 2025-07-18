<?php

namespace App\Livewire;

use Filament\Facades\Filament;
use Livewire\Component;

class PublicHealthGoButton extends Component
{
    public $teamId;

    public function mount()
    {
        $this->teamId = Filament::getTenant()->id;
    }
    public function render()
    {
        return view('livewire.public-health-go-button');
    }
}
