<?php

namespace App\Filament\Resources\Quality\Records\Cleaning\StablishmentAreaResource\Pages;

use App\Filament\Resources\Quality\Records\Cleaning\StablishmentAreaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStablishmentArea extends EditRecord
{
    protected static string $resource = StablishmentAreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
