<?php
namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SaleService
{    
    protected $invoiceService;
    /**
     * SaleService constructor.
     *
     * @param InvoiceService $invoiceService
     */
    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }
    /**
     * Procesa la venta a partir del carrito, del id del cliente y del usuario.
     *
     * @param object $tenant      Objeto tenant obtenido (por ej. de Filament).
     * @param array  $cart        Array de items del carrito.
     * @param mixed  $customerId  ID del cliente.
     * @param mixed  $userId      ID del usuario (Auth::id()).
     *
     * @return \App\Models\Sale
     */
    public function processSale(array $cart, ?int $customerId): Sale
    {
        $tenant = Filament::getTenant();

        // Calcula totales
        $total = 0;
        $itemsData = [];

        foreach ($cart as $item) {
            $qty   = intval($item['sell_quantity']);
            $price = floatval($item['sale_price']);
            $total += $price * $qty;
            $itemsData[] = [
                'qty'          => $qty,
                'price'        => $price,
                'inventory_id' => $item['inventory_id']
            ];
        }

        // Crear Venta
        $sale = Sale::create([
            'team_id'     => $tenant->id,
            'customer_id' => $customerId,
            'user_id'     => Auth::id(),
            'total'       => $total,
            'status'      => 'completed',
            'code'        => (new Sale())->generateCode(),
            'data'        => null,
        ]);

        $saleItems = [];
        // Crear cada item relacionado a la venta
        foreach ($itemsData as $item) {
            $saleItems[] = SaleItem::create([
                'sale_id'      => $sale->id,
                'inventory_id' => $item['inventoryId'],
                'quantity'     => $item['qty'],
                'sale_price'   => $item['price'],
                'total'        => $item['price'] * $item['qty'],
            ]);
        }

        // Generar la factura a partir de la venta y sus items.
        $this->invoiceService->generateInvoice($sale, $saleItems);

        return $sale;
    }
}
