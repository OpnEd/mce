<?php

namespace App\Filament\Resources\Quality\ResiduoResource\Pages;

use App\Filament\Resources\Quality\ResiduoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResiduo extends EditRecord
{
    protected static string $resource = ResiduoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
