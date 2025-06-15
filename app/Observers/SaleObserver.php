<?php

namespace App\Observers;

use App\Models\Sale;
use Illuminate\Support\Facades\Log;

class SaleObserver
{
    /**
     * Handle the Sale "updated" event.
     * Detecta cambios en el campo `status` para ajustar el inventario:
     * - De 'pending' a 'completed': decrementa stock.
     * - De 'completed' a 'cancelled': repone stock.
     *
     * @param  \App\Models\Sale  $sale
     * @return void
     */
    public function updated(Sale $sale): void
    {
        $originalStatus = $sale->getOriginal('status');
        $currentStatus = $sale->status;

        // Cuando se confirma la venta
        if ($originalStatus === 'in-progress' && $currentStatus === 'completed') {
            foreach ($sale->items as $item) {
                $inventory = $item->inventory;
                if ($inventory) {
                    $inventory->decrement('quantity', $item->quantity);
                }
            }

            Log::info("Stock decremented for Sale {$sale->id} confirmation.");
        }

        // Cuando se cancela una venta previamente completada
        if ($originalStatus === 'completed' && $currentStatus === 'cancelled') {
            foreach ($sale->items as $item) {
                $inventory = $item->inventory;
                if ($inventory) {
                    $inventory->increment('quantity', $item->quantity);
                }
            }

            Log::info("Stock replenished for Sale {$sale->id} cancellation.");
        }
    }
}

// Registro del observer en AppServiceProvider.php (método boot):
// use App\Models\Sale;
// use App\Observers\SaleObserver;
// 
// public function boot()
// {
//     Sale::observe(SaleObserver::class);
// }
