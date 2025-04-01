<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecipebookItem extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'recipebook_id',
        'inventory_id',
        'quantity',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function recipebook()
    {
        return $this->belongsTo(Recipebook::class);
    }
}
