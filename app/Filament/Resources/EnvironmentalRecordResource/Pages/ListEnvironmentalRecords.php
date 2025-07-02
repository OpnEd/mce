<?php

namespace App\Filament\Resources\EnvironmentalRecordResource\Pages;

use App\Filament\Resources\EnvironmentalRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEnvironmentalRecords extends ListRecords
{
    protected static string $resource = EnvironmentalRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\EnvironmentalRecordResource\Widgets\TempChart::class,
            \App\Filament\Resources\EnvironmentalRecordResource\Widgets\HumChart::class,           
        ];
    }
}
