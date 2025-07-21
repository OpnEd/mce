<?php

namespace App\Filament\Pos\Resources\ProductReceptionResource\Pages;

use App\Filament\Pos\Resources\ProductReceptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProductReception extends ViewRecord
{
    protected static string $resource = ProductReceptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
