<?php

namespace App\Filament\Pos\Widgets;

use App\Models\PeripheralProductPrice;
use Illuminate\Support\Facades\DB;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ReplenableProducts extends BaseWidget
{
    protected static ?int $sort = 4;
    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->heading(__('Replenable Products'))
            ->query(
                PeripheralProductPrice::query()
                    ->from('peripheral_product_price')
                    ->join('inventories as i', function ($join) {
                        $join->on('peripheral_product_price.product_id', '=', 'i.product_id')
                            ->on('peripheral_product_price.team_id', '=', 'i.team_id');
                    })
                    ->join('products as p', 'peripheral_product_price.product_id', '=', 'p.id')
                    ->select(
                        'peripheral_product_price.id as id',
                        'peripheral_product_price.product_id',
                        'p.name as product_name',
                        'p.bar_code as product_code',
                        'peripheral_product_price.team_id',
                        'peripheral_product_price.min_stock',
                        'peripheral_product_price.sale_price',
                        DB::raw('SUM(i.quantity) as total_quantity')
                    )
                    ->groupBy(
                        'peripheral_product_price.id',
                        'peripheral_product_price.product_id',
                        'p.name',
                        'p.bar_code',
                        'peripheral_product_price.team_id',
                        'peripheral_product_price.min_stock',
                        'peripheral_product_price.sale_price'
                    )
                    ->havingRaw('SUM(i.quantity) < peripheral_product_price.min_stock')
                )
                ->columns([
                    Tables\Columns\TextColumn::make('product_code')
                        ->label(__('Code'))
                        ->searchable(),
                    Tables\Columns\TextColumn::make('product_name')
                        ->label(__('Product'))
                        ->searchable(),
                    Tables\Columns\TextColumn::make('total_quantity')->label(__('Current Stock'))
                        ->numeric(),
                    Tables\Columns\TextColumn::make('min_stock')->label(__('Minimum Stock'))
                        ->numeric(),
                ]);
    }
}