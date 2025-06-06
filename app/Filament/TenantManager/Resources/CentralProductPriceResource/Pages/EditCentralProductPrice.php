<?php

namespace App\Filament\TenantManager\Resources\CentralProductPriceResource\Pages;

use App\Filament\TenantManager\Resources\CentralProductPriceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentralProductPrice extends EditRecord
{
    protected static string $resource = CentralProductPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
