<?php

namespace App\Filament\Resources\PurchaseItemResource\Pages;

use App\Filament\Resources\PurchaseItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\CentralProductPrice;

class CreatePurchaseItem extends CreateRecord
{
    protected static string $resource = PurchaseItemResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Obtener precio desde CentralProductPrice
        $price = CentralProductPrice::where('product_id', $data['product_id'])
            ->firstOrFail()
            ->price;

        // Calcular total del ítem
        $data['price'] = $price;
        $data['total'] = $data['quantity'] * $price;

        return $data;
    }
/*
    protected function afterCreate(): void
    {
        $this->updatePurchaseTotal();
    }

    protected function afterUpdate(): void
    {
        $this->updatePurchaseTotal();
    }

    protected function afterDelete(): void
    {
        $this->updatePurchaseTotal();
    }

    private function updatePurchaseTotal(): void
    {
        $purchase = $this->getOwner()->getRecord();
        $purchase->update([
            'total' => $purchase->items()->sum('total')
        ]);
    }
        */
}
