<?php

namespace App\Filament\Resources\PurchaseResource\RelationManagers;

use App\Filament\Resources\PurchaseResource;
use App\Helpers\PermissionVerificationHelper;
use App\Models\CentralProductPrice;
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
    protected static ?string $model = Purchase::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name', function ($query) {
                        // Aquí aplicamos el scope inStock()
                        $query->inStock();
                    })
                    ->searchable()
                    ->afterStateUpdated(function (?string $state, Set $set, Get $get) {
                        // Calcular y persistir price y total aunque no haya inputs
                        $price = CentralProductPrice::find($get('product_id'))?->price ?? 0;
                        $set('price', $price);
                        $set('total', $state * $price);
                    })
                    ->live()
                    ->required(),
                Forms\Components\TextInput::make('quantity')
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
                Forms\Components\Hidden::make('price')
                    ->default(0),
                Forms\Components\Hidden::make('total')
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_id')
            ->columns([
                Tables\Columns\TextColumn::make('product.name'),
                Tables\Columns\TextColumn::make('quantity')
                    ->sortable()
                    ->numeric(),
                Tables\Columns\TextColumn::make('price')
                    ->sortable()
                    ->numeric()
                    ->prefix('$'),
                Tables\Columns\TextColumn::make('total')
                    ->sortable()
                    ->numeric()
                    ->prefix('$'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add product')
                    ->icon('phosphor-plus')
                    ->visible(fn(): bool => Gate::allows('confirm', $this->ownerRecord))
                    ->after(function ($record) {
                        DB::transaction(function () use ($record) {
                            $record->purchase->updatePurchaseTotal();
                            $this->dispatch('purchaseTotalUpdated');
                        });
                    }),
                Tables\Actions\Action::make('confirmPurchase')
                    ->label('Confirm Purchase')
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
                            DB::transaction(function () use ($record) {
                                $record->purchase->updatePurchaseTotal();
                                $this->dispatch('purchaseTotalUpdated');
                            });
                        }),
                    Tables\Actions\DeleteAction::make()
                        ->after(function ($record) {
                            DB::transaction(function () use ($record) {
                                $record->purchase->updatePurchaseTotal();
                                $this->dispatch('purchaseTotalUpdated');
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
