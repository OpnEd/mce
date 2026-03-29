<?php

namespace App\Filament\Resources\Quality\Training\ModuleResource\Pages;

use App\Filament\Resources\Quality\Training\ModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewModule extends ViewRecord
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}