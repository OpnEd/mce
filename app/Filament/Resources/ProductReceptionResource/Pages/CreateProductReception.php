<?php

namespace App\Filament\Resources\ProductReceptionResource\Pages;

use App\Filament\Resources\ProductReceptionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductReception extends CreateRecord
{
    protected static string $resource = ProductReceptionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
