<?php

namespace App\Filament\Pos\Resources\PurchaseResource\RelationManagers;

use App\Filament\Pos\Resources\PurchaseResource;
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
    protected static ?string $model = Purchase::class;


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->searchable()
                    ->optionsLimit(200)
                    ->getSearchResultsUsing(
                        fn(string $search) => Product::withoutGlobalScopes()
                            ->where('drug', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->get()
                            ->mapWithKeys(function ($product) {
                                // Muestra el nombre y el SKU juntos
                                return [
                                    $product->id => "{$product->drug} ({$product->description})"
                                ];
                            })
                            ->toArray()
                    )
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\Hidden::make('price')
                    ->default(0),
                Forms\Components\Hidden::make('total')
                    ->default(0),
            ]);
    }

    /**
     * Query builder sin ningún scope global.
     */
    /* public static function withoutScopes(): Builder
    {
        return static::query()->withoutGlobalScopes();
    } */

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_id')
            ->columns([
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('product.name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add product')
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
                    ->after(function ($record) {
                        DB::transaction(function ($livewire) use ($record) {
                            $record->purchase->updatePurchaseTotal();
                        });
                    }),
                Tables\Actions\Action::make('confirmPurchase')
                    ->label('Confirm Purchase')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->visible(
                        fn(): bool =>
                            Gate::allows('confirm', $this->ownerRecord)
                            && $this->ownerRecord->items()->count() > 0
                            && $this->ownerRecord->status === 'in_progress'
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
}
