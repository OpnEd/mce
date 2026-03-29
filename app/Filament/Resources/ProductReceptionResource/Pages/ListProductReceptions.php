<?php

namespace App\Filament\Resources\ProductReceptionResource\Pages;

use App\Filament\Resources\ProductReceptionResource;
use App\Filament\Resources\Quality\Records\Products\PurchaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductReceptions extends ListRecords
{
    protected static string $resource = ProductReceptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('verOrdenesDeCompra')
                ->label('Ver ordenes de compra')
                ->icon('heroicon-o-shopping-cart')
                ->url(fn (): string => PurchaseResource::getUrl('index'))
                ->color('info'),
        ];
    }
}
