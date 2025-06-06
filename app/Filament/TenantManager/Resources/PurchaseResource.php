<?php

namespace App\Filament\TenantManager\Resources;

use App\Filament\TenantManager\Resources\PurchaseResource\Pages;
use App\Filament\TenantManager\Resources\PurchaseResource\RelationManagers;
use App\Models\Purchase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redirect;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static ?string $navigationGroup = 'Transactions';
    protected static ?string $navigationIcon = 'phosphor-shopping-cart';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'confirmed')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', 'confirmed')->count() > 10 ? 'warning' : 'primary';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Número de órdenes de compra confirmadas';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Order details')
                    ->schema([
                        Forms\Components\TextInput::make('id')
                            ->label('# Pruchase')
                            ->readOnly(),
                        Forms\Components\Select::make('team_id')
                            ->relationship('team', 'name')
                            ->required(),
                        Forms\Components\Select::make('supplier_id')
                            ->relationship('supplier', 'name')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'in progress' => 'In Progress',
                                'ready' => 'Ready',
                                'dispatched' => 'Dispatched',
                                'delivered' => 'Delivered',
                            ]),
                        Forms\Components\TextInput::make('total')
                            ->readOnly()
                            ->prefix('$'),
                    ])
                    ->columns(4)
                    ->collapsed(),
                Section::make('Order meta-data')
                    ->schema([
                        Forms\Components\Textarea::make('observations'),
                        Forms\Components\KeyValue::make('data')
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('# Purchase')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('team.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'danger',
                        'confirmed' => 'primary',
                        'in progress' => 'info',
                        'ready' => 'amber',
                        'dispatched' => 'gray',
                        'delivered' => 'success',
                    }),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Enlist items')
                        ->icon('phosphor-check-square')
                        ->visible(fn(Purchase $record): bool => $record->status === 'confirmed' && $record->items()->where('enlisted', '!=', 1)->exists()),
                    Action::make('createDispatch')
                        ->label('Dispatch')
                        ->icon('heroicon-o-truck')
                        ->action(function (Model $record, array $data): void {

                            // Verificar si todos los PurchaseItems están enlistados
                            if ($record->items()->where('enlisted', '!=', 1)->exists()) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Falta verificar productos')
                                    ->color('danger')
                                    ->send();
                                return;
                            }

                            // Usar el servicio para crear el Dispatch y cambiar de estado
                            // a 'in progress'
                            $dispatch = app(\App\Services\DispatchService::class)->createFromPurchase($record);

                            // Redirigir al edit del Dispatch recién creado
                            Redirect::to(
                                \App\Filament\TenantManager\Resources\DispatchResource::getUrl('edit', ['record' => $dispatch->id])
                            );
                        })
                        ->requiresConfirmation()
                        ->color('info')
                        ->visible(fn(Purchase $record): bool => $record->status === 'confirmed' && $record->items()->where('enlisted', '!=', 1)->doesntExist()),
                    Action::make('editDispatch')
                        ->label('Edit Dispatch')
                        ->icon('phosphor-check-square')
                        ->action(function (Model $record, array $data): void {

                            // Redirigir al edit del Dispatch recién creado
                            Redirect::to(
                                \App\Filament\TenantManager\Resources\DispatchResource::getUrl('edit', ['record' => $record->id])
                            );
                        })
                        ->color('info')
                        ->visible(fn(Purchase $record): bool => $record->status === 'in progress'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'view' => Pages\ViewPurchase::route('/{record}'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
