<?php

namespace App\Filament\TenantManager\Resources\SanitaryRegistryResource\Pages;

use App\Filament\TenantManager\Resources\SanitaryRegistryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSanitaryRegistries extends ListRecords
{
    protected static string $resource = SanitaryRegistryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
