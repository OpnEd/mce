<?php

namespace App\Filament\Resources\Quality\DocumentVersionResource\Pages;

use App\Filament\Resources\Quality\DocumentVersionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentVersion extends EditRecord
{
    protected static string $resource = DocumentVersionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
