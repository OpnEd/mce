<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PurchaseItem extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseItemFactory> */
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'price',
        'total',
        'enlisted',
    ];

    protected $casts = [
        'enlisted' => 'boolean',
        'total' => 'decimal:2',
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function dispatchItem(): HasMany
    {
        return $this->hasMany(DispatchItems::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    // Para calcular el total antes de guardar el PurhcaseItem
    /* public static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->total = $item->quantity * $item->price;
        });
    } */
}
