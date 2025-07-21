<?php

namespace App\Filament\Pos\Resources\ProductReceptionItemResource\Pages;

use App\Filament\Pos\Resources\ProductReceptionItemResource;
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
