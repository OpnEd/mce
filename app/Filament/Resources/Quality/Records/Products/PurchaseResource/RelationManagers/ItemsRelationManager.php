<?php

namespace App\Filament\Resources\PurchaseResource\RelationManagers;

use App\Filament\Resources\Quality\Records\Products\PurchaseResource;
use App\Helpers\PermissionVerificationHelper;
use App\Models\CentralProductPrice;
use App\Models\Product;
use App\Models\Purchase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Products';
    //protected static ?string $model = Purchase::class;


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label(__('fields.product'))
                    ->searchable()
                    ->relationship('product', 'name')
                    ->getSearchResultsUsing(       // callback personalizado
                        fn(string $search) => Product::withoutGlobalScopes()
                            ->where('name', 'like', "%{$search}%")
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->afterStateUpdated(function (?string $state, Set $set, Get $get) {
                        // Obtener la cantidad actual o 1 si es nulo
                        $quantity = $get('quantity') ?? 1;
                        // Buscar el precio actualizado del producto seleccionado
                        $price = CentralProductPrice::find($state)?->price ?? 0;
                        $set('price', $price);
                        $set('total', $quantity * $price);
                    })
                    ->live()
                    ->required(),

                Forms\Components\TextInput::make('quantity')
                    ->label(__('Quantity'))
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->afterStateUpdated(function (?string $state, Set $set, Get $get) {
                        // Calcular y persistir price y total aunque no haya inputs
                        $price = CentralProductPrice::find($get('product_id'))?->price ?? 0;
                        $set('price', $price);
                        $set('total', $state * $price);
                    })
                    ->live(),

                Forms\Components\Select::make('type')
                    ->label('Tipo de Faltante')
                    ->helperText(str('**Faltante Ordinario**: Producto a solicitar que aún tiene existencias. **Faltante Efectivo**: Producto de alta rotación solicitado por los usuarios y que no pudimos dispensar por existencias cero (0). **Faltante Baja Rotación**: Producto de baja rotación solicitado por los usuarios y que no pudimos dispensar por existencias cero (0)')->inlineMarkdown()->toHtmlString())
                    ->options([
                        'faltante_ordinario' => 'Faltante Ordinario',
                        'faltante_efectivo' => 'Faltante Efectivo',
                        'faltante_baja_rotacion' => 'Faltante Baja Rotación',
                    ]) // ->default('faltante_ordinario')
                    ->default('faltante_ordinario')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Hidden::make('price')
                    ->default(0),
                Forms\Components\Hidden::make('total')
                    ->default(0),

                // --- Campo para forzar team_id igual al del Purchase dueño ---
                Forms\Components\Hidden::make('team_id')
                    ->default(function () {
                        // Si hay owner record, tomar su team_id; si no, tomar team del usuario
                        $owner = $this->getOwnerRecord();
                        if ($owner && isset($owner->team_id)) {
                            return $owner->team_id;
                        }
                        return Auth::user()?->team_id ?? null;
                    })
                    ->dehydrated(true)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_id')
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('Product'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('Quantity'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo de Faltante')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('Price'))
                    ->money('cop', true)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Agregar producto')
                    ->icon('phosphor-plus')
                    ->visible(fn(): bool => Gate::allows('confirm', $this->ownerRecord))
                    ->before(function (array $data, $action) {
                        $purchase = $this->ownerRecord;
                        $exists = $purchase->items()->where('product_id', $data['product_id'])->exists();
                        // si ya existe el producto en la orden, no permitir agregarlo de nuevo
                        if ($exists) {
                            \Filament\Notifications\Notification::make()
                                ->title('Este producto ya fue agregado')
                                ->body('No puedes agregar el mismo producto dos veces a la orden, pero sí puedes cambiar la cantidad en el registro.')
                                ->danger()
                                ->send();

                            // Cancelar la acción correctamente
                            $action->cancel();
                        }
                    })
                    // FORZAR team_id desde el Purchase dueño antes de crear
                    ->mutateFormDataUsing(function (array $data) {
                        $owner = $this->getOwnerRecord();
                        if ($owner && isset($owner->team_id)) {
                            $data['team_id'] = $owner->team_id;
                        } else {
                            $data['team_id'] = Auth::user()?->team_id ?? null;
                        }
                        return $data;
                    })
                    ->after(function ($record) {
                        DB::transaction(function ($livewire) use ($record) {
                            $record->purchase->updatePurchaseTotal();
                        });
                    }),
                Tables\Actions\Action::make('confirmPurchase')
                    ->label('Confirmar Compra')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->visible(
                        fn(): bool =>
                        Gate::allows('confirm', $this->ownerRecord)
                            &&
                            $this->ownerRecord->items()->count() > 0
                    )
                    ->requiresConfirmation()
                    ->action(function () {
                        $purchase = $this->ownerRecord;
                        try {
                            DB::transaction(function () use ($purchase) {
                                $purchase->update([
                                    'status' => 'confirmed',
                                    'confirmed_at' => now(),
                                ]);
                            });

                            \Filament\Notifications\Notification::make()
                                ->title('Order confirmed')
                                ->color('success')
                                ->send();

                            // Redireccionar a la página 'index'
                            //return redirect()->route('filament.admin.resources.purchases.index');


                            // Redirigir al formulario de edición de este Purchase
                            Redirect::to(
                                PurchaseResource::getUrl('index')
                            );
                        } catch (\Exception $e) {

                            \Filament\Notifications\Notification::make()
                                ->title('Error al confirmar')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();

                            throw $e;
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->after(function ($record) {
                            DB::transaction(function ($livewire) use ($record) {
                                $record->purchase->updatePurchaseTotal();
                            });
                        }),
                    Tables\Actions\DeleteAction::make()
                        ->after(function ($record) {
                            DB::transaction(function ($livewire) use ($record) {
                                $record->purchase->updatePurchaseTotal();
                            });
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /* protected function afterCreate(): void
    {
        $this->recalculatePurchaseTotal();
    }

    protected function afterSave(): void
    {
        $this->recalculatePurchaseTotal();
    }

    protected function afterDelete(): void
    {
        $this->recalculatePurchaseTotal();
    }

    protected function recalculatePurchaseTotal(): void
    {
        $purchase = $this->getOwnerRecord(); // modelo Purchase
        $total = $purchase->items()->sum('total');

        $purchase->update(['total' => $total]);
    } */
}
