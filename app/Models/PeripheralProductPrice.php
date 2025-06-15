<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PeripheralProductPrice extends Pivot
{
    /** @use HasFactory<\Database\Factories\CentralProductPriceFactory> */
    use HasFactory;

    protected $fillable = [
        'team_id',
        'product_id',
        'min', //stock mínimo
        'sale_price',
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    // Relación inversa: cada precio pertenece a un producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
