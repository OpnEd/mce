<?php

namespace App\Filament\Pos\Resources\ProductReceptionResource\Pages;

use App\Filament\Pos\Resources\ProductReceptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductReception extends EditRecord
{
    protected static string $resource = ProductReceptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
