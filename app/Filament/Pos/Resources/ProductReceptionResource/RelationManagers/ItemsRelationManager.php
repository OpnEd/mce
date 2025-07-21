<?php

namespace App\Filament\Pos\Resources\ProductReceptionResource\RelationManagers;

use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('batch_id')
                    ->relationship(
                        'batch',
                        'code',
                        fn(Builder $query) => $query->where('team_id', Filament::getTenant()?->id ?? null)
                    )
                    ->label(__('Batch Code'))
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        // Relación con SanitaryRegistry
                        Forms\Components\Select::make('sanitary_registry')
                            ->label('Registro Sanitario')
                            ->options(Product::all()->pluck('registro_sanitario','registro_sanitario'))
                            ->searchable()
                            ->preload()
                            ->required(),

                        // Relación con Manufacturer
                        Forms\Components\Select::make('manufacturer_id')
                            ->label('Fabricante')
                            ->relationship('manufacturer', 'name')
                            ->searchable()
                            ->required(),

                        // Código único
                        Forms\Components\TextInput::make('code')
                            ->label('Código de Lote')
                            ->required()
                            ->unique(ignoreRecord: true),

                        // Fechas de fabricación y caducidad
                        Forms\Components\DatePicker::make('manufacturing_date')
                            ->label('Fecha de Fabricación')
                            ->required(),
                        Forms\Components\DatePicker::make('expiration_date')
                            ->label('Fecha de Caducidad')
                            ->required(),

                        // Datos adicionales en JSON
                        Forms\Components\KeyValue::make('data')
                            ->label('Información Adicional')
                            ->keyLabel('Campo')
                            ->valueLabel('Valor')
                            ->columnSpanFull(),
                    ])
                    ->required()
                    ->columnSpanFull()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_id')
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('Product Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('batch.code')
                    ->label(__('Batch Code'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('purchase_price'),
                Tables\Columns\TextColumn::make('total')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
                Action::make('confirmReception')
                    ->label('Confirmar Recepción')
                    ->icon('heroicon-o-check')             // o el ícono que prefieras
                    ->requiresConfirmation()                // pide “¿Estás seguro?”
                    ->color('success')
                    ->visible(fn(): bool => $this->ownerRecord->items()->count() > 0)
                    ->action(function (
                         $action
                        //$livewire
                    ) {
                        $productReception = $this->ownerRecord;
                        //$pr = $livewire->ownerRecord;       // tu ProductReception// Validar que todos tengan lote asignado
                        if ($productReception->items()->whereNull('batch_id')->exists()) {
                            //throw new \Exception('¡Error! Todos los productos deben tener un lote asignado.');
                            \Filament\Notifications\Notification::make()
                                ->title('Ups!')
                                ->body('Todos los productos deben tener un lote asignado.')
                                ->danger()
                                ->send();

                            // Cancelar la acción correctamente
                            $action->cancel();
                        }
                        DB::transaction(function () use ($productReception) {
                            foreach ($productReception->items as $item) {
                                Inventory::updateOrCreate(
                                    [
                                        'team_id'    => $productReception->team_id,
                                        'product_id' => $item->product_id,
                                        'batch_id'   => $item->batch_id,
                                    ],
                                    [
                                        // suma la cantidad existente + la nueva
                                        'quantity'       => DB::raw("quantity + {$item->quantity}"),
                                        'purchase_price' => $item->purchase_price,
                                        'product_name'   => $item->product->name,
                                    ]
                                );
                            }
                            // Marcar la recepción como “done”
                            $productReception->update(['status' => 'done']);
                            $productReception->save();
                        });

                        Notification::make()
                            ->title('Recepción confirmada')
                            ->body('Se ha agregado el inventario y cambiado el estado a DONE.')
                            ->success()
                            ->send();

                        // Refresca la tabla del RelationManager
                        //$livewire->dispatch('refreshRelationManager');
                    }),
                /* Action::make('confirmReception')
                    ->label('Confirmar Recepción')
                    ->icon('heroicon-o-check')             // o el ícono que prefieras
                    ->requiresConfirmation()                // pide “¿Estás seguro?”
                    ->color('success')
                    ->action(function ($record, $data, $action) {
                        // Obtener el ownerRecord desde el contexto de la acción
                    $ownerRecord = $action->getTable()->getRelationship()->getParent();
dd($ownerRecord);
                        // Validar que todos tengan lote asignado
                        if ($ownerRecord->items()->whereNull('batch_id')->exists()) {
                            //throw new \Exception('¡Error! Todos los productos deben tener un lote asignado.');
                            \Filament\Notifications\Notification::make()
                                ->title('Ups!')
                                ->body('Todos los productos deben tener un lote asignado.')
                                ->danger()
                                ->send();

                            // Cancelar la acción correctamente
                            $action->cancel();
                        }


                        DB::transaction(function () use ($ownerRecord) {
                            foreach ($ownerRecord->items as $item) {
                                // Buscar inventario existente
                                $inventory = \App\Models\Inventory::firstOrNew([
                                    'product_id' => $item->product_id,
                                    'batch_id' => $item->batch_id,
                                    'team_id' => $ownerRecord->team_id,
                                ]);
                            
                            // Actualizar cantidades
                            $inventory->quantity = ($inventory->quantity ?? 0) + $item->quantity;
                            
                            // Setear valores solo para nuevos registros
                            if (!$inventory->exists) {
                                $inventory->purchase_price = $item->purchase_price;
                                $inventory->product_name = $item->product->name;
                            }

                                $inventory->save();
                            }

                            // Actualizar estado de la recepción
                            $ownerRecord->update(['status' => 'done']);
                        });

                        Notification::make()
                            ->title('Recepción confirmada')
                            ->body('Se ha agregado el inventario y cambiado el estado a DONE.')
                            ->success()
                            ->send();
                    })
                    ->hidden(fn($record) => $record->status === 'done') */
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('Assign lot')),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
