<?php

namespace App\Filament\Pos\Resources\PurchaseResource\Pages;

use App\Filament\Pos\Resources\PurchaseResource;
use App\Models\Supplier;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['supplier_id'] = Supplier::find(10)->id;
        return $data;
    }
}
