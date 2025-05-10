<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentralProductPrice extends Model
{
    /** @use HasFactory<\Database\Factories\CentralProductPriceFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'min', //stock mínimo
        'price',
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
