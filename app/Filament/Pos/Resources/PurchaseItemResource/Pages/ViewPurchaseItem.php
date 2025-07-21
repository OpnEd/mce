<?php

namespace App\Filament\Pos\Resources\PurchaseItemResource\Pages;

use App\Filament\Pos\Resources\PurchaseItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPurchaseItem extends ViewRecord
{
    protected static string $resource = PurchaseItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
