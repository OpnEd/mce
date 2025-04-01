<?php

namespace App\Filament\TenantManager\Resources\ProductCategoryResource\Pages;

use App\Filament\TenantManager\Resources\ProductCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductCategory extends CreateRecord
{
    protected static string $resource = ProductCategoryResource::class;
}
