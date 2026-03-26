<?php

namespace App\Filament\Resources\Quality\Records\Products\ProductReturnResource\Pages;

use App\Filament\Resources\Quality\Records\Products\ProductReturnResource;
use App\Filament\Resources\Quality\Records\Products\ProductReturnResource\Widgets\ProductReturnMonthlyTypeChart;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductReturns extends ListRecords
{
    protected static string $resource = ProductReturnResource::class;

    public function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
