<?php

namespace App\Filament\TenantManager\Resources\PharmaceuticalFormResource\Pages;

use App\Filament\TenantManager\Resources\PharmaceuticalFormResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPharmaceuticalForm extends ViewRecord
{
    protected static string $resource = PharmaceuticalFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
