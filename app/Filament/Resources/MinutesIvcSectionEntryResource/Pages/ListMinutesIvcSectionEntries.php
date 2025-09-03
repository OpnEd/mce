<?php

namespace App\Filament\Resources\MinutesIvcSectionEntryResource\Pages;

use App\Filament\Resources\MinutesIvcSectionEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMinutesIvcSectionEntries extends ListRecords
{
    protected static string $resource = MinutesIvcSectionEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
