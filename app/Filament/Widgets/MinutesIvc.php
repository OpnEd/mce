<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class MinutesIvc extends Widget
{
    protected static string $view = 'filament.widgets.minutes-ivc';
    protected int | string | array $columnSpan = 'full';
}
