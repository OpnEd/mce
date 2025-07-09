<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductReceptionItem extends Model
{
    /** @use HasFactory<\Database\Factories\ProductReceptionItemFactory> */
    use HasFactory;

    protected $fillable = [
        'product_reception_id',
        'product_id',
        'batch_id',
        'quantity',
        'purchase_price',
        'total'
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productReception(): BelongsTo
    {
        return $this->belongsTo(ProductReception::class);
    }
}
