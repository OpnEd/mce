<?php

namespace App\Filament\Resources\PurchaseResource\Pages;

use App\Models\Purchase;
use App\Filament\Resources\PurchaseResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Redirect;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPurchases extends ListRecords
{
    protected static string $resource = PurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createWithDefaults')
                ->label('Go shopping!')
                ->icon('phosphor-shopping-bag')
                ->action(function () {
                    // 1️⃣ Crear el Purchase con valores por defecto
                    $purchase = Purchase::create([
                        'team_id'       => Filament::getTenant()->id,
                        'supplier_id'   => 1,
                        'status'        => 'pending',  // ejemplo
                        'observations'  => null,
                        'total'         => 0,
                        'data'          => [],
                        // …otros campos por defecto…
                    ]);

                    // 2️⃣ Redirigir al formulario de edición de este Purchase
                    Redirect::to(
                        PurchaseResource::getUrl('edit', ['record' => $purchase->id])
                    );
                })
                ->color('primary'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'pending' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status','pending')),
            'confirmed' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status','confirmed')),
            'in progress' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status','in progress')),
            'ready' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status','ready')),
            'dispatched' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status','dispatched')),
            'delivered' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status','delivered')),
        ];
    }
}
