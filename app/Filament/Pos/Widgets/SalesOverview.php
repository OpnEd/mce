<?php

namespace App\Filament\Pos\Widgets;

use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Sale;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make("Total Sales", Sale::count()),
            Stat::make("Total Products", Inventory::count()),
            Stat::make("Total Customers", Customer::count()),
        ];
    }
}
