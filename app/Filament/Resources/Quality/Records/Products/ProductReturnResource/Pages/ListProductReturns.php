<?php

namespace App\Filament\Resources\Quality\Records\Products\ProductReturnResource\Pages;

use App\Filament\Resources\Quality\Records\Products\ProductReturnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductReturns extends ListRecords
{
    protected static string $resource = ProductReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
