<?php

namespace App\Filament\TenantManager\Resources\CentralProductPriceResource\Pages;

use App\Filament\TenantManager\Resources\CentralProductPriceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentralProductPrices extends ListRecords
{
    protected static string $resource = CentralProductPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
