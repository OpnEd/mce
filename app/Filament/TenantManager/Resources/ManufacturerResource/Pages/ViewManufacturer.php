<?php

namespace App\Filament\TenantManager\Resources\ManufacturerResource\Pages;

use App\Filament\TenantManager\Resources\ManufacturerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewManufacturer extends ViewRecord
{
    protected static string $resource = ManufacturerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
