<?php

namespace App\Filament\Pos\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Page;

class Sales extends Page
{
    protected static ?string $navigationIcon = 'phosphor-cash-register';

    protected static ?string $navigationGroup = 'POS';
    
    public $teamId;

    public function mount()
    {
        $this->teamId = Filament::getTenant()->id;
    }
    protected static string $view = 'filament.pos.pages.sales';
}
