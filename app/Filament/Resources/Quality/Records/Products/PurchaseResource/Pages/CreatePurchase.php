<?php

namespace App\Filament\Resources\Quality\Records\Products\PurchaseResource\Pages;

use App\Filament\Resources\Quality\Records\Products\PurchaseResource;
use App\Models\Purchase;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['team_id'] = Filament::getTenant()->id;
        $data['status'] = 'in_progress';
        $data['code'] = (new Purchase())->generatePurchaseCode();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Order registered')
            ->body('The order has been created successfully, you must now add products');
    }
}
