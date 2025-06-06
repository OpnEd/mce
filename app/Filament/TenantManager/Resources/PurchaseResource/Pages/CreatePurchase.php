<?php

namespace App\Filament\TenantManager\Resources\PurchaseResource\Pages;

use App\Filament\TenantManager\Resources\PurchaseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;
}
