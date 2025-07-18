<?php

namespace App\Filament\TenantManager\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Page;

class Events extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    public $teamId;

    public function mount()
    {
        $this->teamId = Filament::getTenant();
        dd($this->teamId);
    }

    protected static string $view = 'filament.tenant-manager.pages.events';
}
