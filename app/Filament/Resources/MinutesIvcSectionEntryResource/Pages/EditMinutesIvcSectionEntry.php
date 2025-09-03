<?php

namespace App\Filament\Resources\MinutesIvcSectionEntryResource\Pages;

use App\Filament\Resources\MinutesIvcSectionEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMinutesIvcSectionEntry extends EditRecord
{
    protected static string $resource = MinutesIvcSectionEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
