<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ActaDashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.acta-dashboard';
    protected static string $routePath = 'secretaria-de-salud';
    
    protected static ?string $navigationGroup = 'Auditoría IVC';
    protected static ?string $title = 'Tabla de Contenidos';

    
}
