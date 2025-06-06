<?php

namespace App\Filament\TenantManager\Resources\DispatchItemsResource\Pages;

use App\Filament\TenantManager\Resources\DispatchItemsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDispatchItems extends EditRecord
{
    protected static string $resource = DispatchItemsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
