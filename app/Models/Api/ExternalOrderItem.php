<?php

namespace App\Models\Api;

use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExternalOrderItem extends Model
{
    protected $fillable = [
        'external_order_id',
        'product_id',
        'sku',
        'name',
        'qty',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(ExternalOrder::class, 'external_order_id');
    }
}