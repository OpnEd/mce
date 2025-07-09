<?php

namespace App\Filament\Resources\ProductReceptionItemResource\Pages;

use App\Filament\Resources\ProductReceptionItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductReceptionItem extends EditRecord
{
    protected static string $resource = ProductReceptionItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
