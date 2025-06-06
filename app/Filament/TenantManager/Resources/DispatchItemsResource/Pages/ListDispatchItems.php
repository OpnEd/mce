<?php

namespace App\Filament\TenantManager\Resources\DispatchItemsResource\Pages;

use App\Filament\TenantManager\Resources\DispatchItemsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDispatchItems extends ListRecords
{
    protected static string $resource = DispatchItemsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
