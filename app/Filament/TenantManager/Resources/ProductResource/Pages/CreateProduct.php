<?php

namespace App\Filament\TenantManager\Resources\ProductResource\Pages;

use App\Filament\TenantManager\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
}
