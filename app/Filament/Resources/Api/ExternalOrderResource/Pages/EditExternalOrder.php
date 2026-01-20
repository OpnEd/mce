<?php

namespace App\Filament\Resources\Api\ExternalOrderResource\Pages;

use App\Filament\Resources\Api\ExternalOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExternalOrder extends EditRecord
{
    protected static string $resource = ExternalOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
