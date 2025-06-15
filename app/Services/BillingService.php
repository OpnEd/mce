<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class BillingService
{
    /**
     * Crea un Invoice a partir de una Sale.
     *
     * @param  \App\Models\Sale  $sale
     * @return \App\Models\Invoice
     * @throws \Exception Si la compra no está en un estado válido.
     */
    public function createFromSale(Sale $sale): Invoice
    {
        // 1. Crear el Invoice
        $invoice = Invoice::create([
            'team_id'       => $sale->team_id,
            'sale_id'       => $sale->id,
            'supplier_id'   => null,
            'code'          => 'INV-' . $sale->id . '-' . now()->format('YmdHis'),
            'amount'        => $sale->total,
            'is_our'        => 1, // el modelo Invoice almacena todas las facturas, incluidas las que vienen de terceros
            'issued_date'   => now(),
            'data'          => $sale->data,
        ]);

        // 2. Clonar cada PurchaseItem en InvoiceItem (sin asignar lote aún)
        $itemsData = $sale->items->map(function ($item) {
            return [
                'sale_item_id' => $item->id,
                'batch_id'     => $item->inventory()->batch_id,
                'quantity'     => $item->quantity,
                'sale_price'   => $item->sale_price,
                'total'        => $item->total,
            ];
        })->toArray();

        $invoice->items()->createMany($itemsData);

        // Retornar el Invoice creado para posteriores operaciones.
        return $invoice;
    }
}
