<?php

namespace App\Filament\Resources\Quality\Records\Products\DispenseRecordResource\Pages;

use App\Filament\Resources\Quality\Records\Products\DispenseRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDispenseRecords extends ListRecords
{
    protected static string $resource = DispenseRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
