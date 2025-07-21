<?php

namespace App\Filament\Pos\Resources\ProductReceptionItemResource\Pages;

use App\Filament\Pos\Resources\ProductReceptionItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProductReceptionItem extends ViewRecord
{
    protected static string $resource = ProductReceptionItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
