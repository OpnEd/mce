<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Roles extends Page
{
    protected static ?string $navigationIcon = 'phosphor-fingerprint';
    protected static ?string $navigationGroup = 'Roles y Permisos';

    protected static string $view = 'filament.pages.roles';
}
