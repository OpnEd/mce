<?php

namespace App\Filament\TenantManager\Resources\SanitaryRegistryResource\Pages;

use App\Filament\TenantManager\Resources\SanitaryRegistryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSanitaryRegistry extends EditRecord
{
    protected static string $resource = SanitaryRegistryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
