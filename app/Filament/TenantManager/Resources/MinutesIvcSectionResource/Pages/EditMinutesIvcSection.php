<?php

namespace App\Filament\TenantManager\Resources\MinutesIvcSectionResource\Pages;

use App\Filament\TenantManager\Resources\MinutesIvcSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMinutesIvcSection extends EditRecord
{
    protected static string $resource = MinutesIvcSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
