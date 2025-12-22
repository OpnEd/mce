<?php

namespace App\Filament\Resources\Quality\Records\Products\PurchaseItemResource\Pages;

use App\Filament\Resources\Quality\Records\Products\PurchaseItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseItem extends EditRecord
{
    protected static string $resource = PurchaseItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
