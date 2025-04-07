<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Permissions extends Page
{
    protected static ?string $navigationIcon = 'phosphor-lock-open';
    protected static ?string $navigationGroup = 'Roles y Permisos';

    protected static string $view = 'filament.pages.permissions';
}
