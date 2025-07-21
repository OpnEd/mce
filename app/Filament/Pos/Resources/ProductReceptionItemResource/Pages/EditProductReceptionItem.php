<?php

namespace App\Filament\Pos\Resources\ProductReceptionItemResource\Pages;

use App\Filament\Pos\Resources\ProductReceptionItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductReceptionItem extends EditRecord
{
    protected static string $resource = ProductReceptionItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
