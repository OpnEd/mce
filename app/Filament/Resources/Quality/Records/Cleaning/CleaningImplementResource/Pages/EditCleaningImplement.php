<?php

namespace App\Filament\Resources\Quality\Records\Cleaning\CleaningImplementResource\Pages;

use App\Filament\Resources\Quality\Records\Cleaning\CleaningImplementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCleaningImplement extends EditRecord
{
    protected static string $resource = CleaningImplementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
