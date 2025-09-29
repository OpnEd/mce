<?php

namespace App\Filament\Resources\Quality\Records\Products\PurchaseResource\Pages;

use App\Filament\Resources\Quality\Records\Products\PurchaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPurchase extends ViewRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Agregar productos'),
        ];
    }
}
