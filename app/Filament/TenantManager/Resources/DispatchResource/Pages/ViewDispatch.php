<?php

namespace App\Filament\TenantManager\Resources\DispatchResource\Pages;

use App\Filament\TenantManager\Resources\DispatchResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDispatch extends ViewRecord
{
    protected static string $resource = DispatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
