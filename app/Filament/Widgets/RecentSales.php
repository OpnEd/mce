<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentSales extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->striped()
            ->heading(__('Recent Sales'))
            ->query(
                Sale::latest() // Fetch the latest 10 sales
            )
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Vendedor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric(),
            ])
            ->actions([
                Action::make('view_invoice')
                    ->label(__('Invoice'))
                    ->icon('phosphor-invoice')
                    ->url(function($record){
                        return $record->factura ? route('invoice.download', $record->factura->id) : null;
                    })
                    ->openUrlInNewTab(), // Allows viewing the sale details
            ]);
    }
}
