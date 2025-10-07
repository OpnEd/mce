<?php

namespace App\Filament\Resources\Quality\Records\Cleaning\CleaningRecordResource\Pages;

use App\Filament\Resources\Quality\Records\Cleaning\CleaningRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCleaningRecord extends EditRecord
{
    protected static string $resource = CleaningRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
