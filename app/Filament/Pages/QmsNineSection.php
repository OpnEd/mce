<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\SaludPublica;
use Filament\Pages\Page;

class QmsNineSection extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static bool $shouldRegisterNavigation = false;

    //protected static ?string $cluster = SaludPublica::class;

    protected static string $view = 'filament.pages.qms-nine-section';
}
