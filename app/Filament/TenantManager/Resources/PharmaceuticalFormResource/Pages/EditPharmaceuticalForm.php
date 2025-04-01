<?php

namespace App\Filament\TenantManager\Resources\PharmaceuticalFormResource\Pages;

use App\Filament\TenantManager\Resources\PharmaceuticalFormResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPharmaceuticalForm extends EditRecord
{
    protected static string $resource = PharmaceuticalFormResource::class;

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
