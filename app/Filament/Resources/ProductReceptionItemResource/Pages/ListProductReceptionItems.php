<?php

namespace App\Filament\Resources\ProductReceptionItemResource\Pages;

use App\Filament\Resources\ProductReceptionItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductReceptionItems extends ListRecords
{
    protected static string $resource = ProductReceptionItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
