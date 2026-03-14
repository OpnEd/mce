<?php

namespace App\Filament\Resources\Quality\Records\Products\MissingProductResource\Pages;

use App\Filament\Resources\Quality\Records\Products\MissingProductResource;
use App\Models\Quality\Records\Products\MissingProduct;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMissingProduct extends EditRecord
{
    protected static string $resource = MissingProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $requestedByUser = ($data['requested_by_user'] ?? false) === true;
        $isSelected = $data['is_selected'] ?? null;

        if ($isSelected === false) {
            $data['requested_by_user'] = true;
            $requestedByUser = true;
        }

        if ($requestedByUser) {
            $data['stock_status'] = MissingProduct::STOCK_STATUS_OUT_OF_STOCK;
            if ($isSelected === null) {
                $data['is_selected'] = true;
            }
        }

        return $data;
    }
}
