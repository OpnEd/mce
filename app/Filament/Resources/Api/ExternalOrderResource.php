<?php

namespace App\Filament\Resources\Api;

use App\Filament\Resources\Api\ExternalOrderResource\Pages;
use App\Filament\Resources\Api\ExternalOrderResource\RelationManagers;
use App\Models\Api\ExternalOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Support\Enums\FontWeight;

class ExternalOrderResource extends Resource
{
    protected static ?string $model = ExternalOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $isScopedToTenant = false;

    public static function shouldRegisterNavigation(): bool
    {
        return false;  // No mostrar en nav (solo vía notificación)
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Información del Cliente')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextEntry::make('customer_name')
                            ->label('Nombre')
                            ->weight(FontWeight::Bold),
                        TextEntry::make('customer_phone')
                            ->label('Teléfono')
                            ->icon('heroicon-m-phone')
                            ->url(fn($state) => "tel:{$state}"),
                        TextEntry::make('customer_address')
                            ->label('Dirección')
                            ->icon('heroicon-m-map-pin')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Detalles de la Orden')
                    ->icon('heroicon-o-shopping-bag')
                    ->schema([
                        TextEntry::make('external_order_id')->label('ID Externo')->copyable(),
                        TextEntry::make('created_at')->label('Fecha')->dateTime(),
                        TextEntry::make('status')->label('Estado')->badge(),
                        TextEntry::make('notes')->label('Notas')->columnSpanFull()->placeholder('N/A'),
                    ])->columns(3),

                Section::make('Productos')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->hiddenLabel()
                            ->schema([
                                TextEntry::make('name')->label('Producto')->columnSpan(2),
                                TextEntry::make('quantity')->label('Cantidad'),
                                TextEntry::make('check')->label('Verificar')
                                    ->state(null)
                                    ->formatStateUsing(fn() => '<input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500">')
                                    ->html(),
                            ])->columns(4),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('external_order_id')->label('ID Externo')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('customer_name')->label('Cliente')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('customer_phone')->label('Teléfono'),
                Tables\Columns\TextColumn::make('status')->label('Estado')->sortable()->badge(),
                Tables\Columns\TextColumn::make('created_at')->label('Creado')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->label('Actualizado')->dateTime()->sortable(),
            ])
            ->filters([
                
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExternalOrders::route('/'),
            'create' => Pages\CreateExternalOrder::route('/create'),
            'view' => Pages\ViewExternalOrder::route('/{record}'),
            'edit' => Pages\EditExternalOrder::route('/{record}/edit'),
        ];
    }
}
