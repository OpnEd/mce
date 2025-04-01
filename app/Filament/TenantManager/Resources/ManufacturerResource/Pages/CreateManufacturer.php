<?php

namespace App\Filament\TenantManager\Resources\ManufacturerResource\Pages;

use App\Filament\TenantManager\Resources\ManufacturerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateManufacturer extends CreateRecord
{
    protected static string $resource = ManufacturerResource::class;
}
