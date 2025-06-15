<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class InvoiceItem extends Pivot
{

    protected $fillable = [
        'invoice_id',
        'sale_item_id',
        'batch_id',
        'due_date',
        'quantity',
        'price',
        'total'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'sale_price' => 'decimal:2',
        'total' => 'decimal:2',
        'due_date' => 'datetime',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function batchs(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function sale_item(): BelongsTo
    {
        return $this->belongsTo(SaleItem::class);
    }
}
