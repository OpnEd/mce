<?php

namespace App\Livewire;

use App\Models\Batch;
use App\Models\Customer;
use App\Models\ManagementIndicator;
use App\Models\Product;
use App\Models\PurchaseItem;
use Filament\Facades\Filament;
use Livewire\Component;

class Renderproduct extends Component
{
    public $products;
    public $teamId;

    public function mount()
    {
        $this->teamId = Filament::getTenant()->id;
        $this->products = Customer::all();
    }
    public function render()
    {
        return view('livewire.renderproduct');
    }
}
