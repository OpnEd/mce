<?php

namespace App\Livewire\Pos;

use Livewire\Component;

class CustomerInfo extends Component
{
    public $record;

    public function mount($record = null)
    {
        $this->record = $record;
    }
    
    public function render()
    {
        return view('livewire.pos.customer-info');
    }
}
