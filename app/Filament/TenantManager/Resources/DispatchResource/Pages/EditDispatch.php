<?php

namespace App\Filament\TenantManager\Resources\DispatchResource\Pages;

use App\Filament\TenantManager\Resources\DispatchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDispatch extends EditRecord
{
    protected static string $resource = DispatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
