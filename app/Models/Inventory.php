<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'team_id',
        'product_id',
        'bar_code',
        'product_name',
        'batch_id',
        'batch_code',
        'quantity',
        'purchase_price',
        //'sale_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'purchase_price' => 'decimal:2',
        //'sale_price' => 'decimal:2',
    ];

    public function sale_items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
