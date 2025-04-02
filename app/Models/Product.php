<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_category_id', //categoría del producto
        'pharmaceutical_form_id', //forma farmacéutica, si aplica
        'code', //código
        'name', //nombre comercial
        'drug', //principio activo
        'description', //presentación comercial
        'fractionable', //fraccionable
        'conversion_factor', //factor de conversión
        'image', // imagen
        'tax', //impuesto
        'status' //estado:activo o inactivo
    ];

    protected function casts(): array
    {
        return [
            'code' => 'string',
            'name' => 'string',
            'drug' => 'string',
            'description' => 'string',
            'fractionable' => 'boolean',
            'conversion_factor' => 'decimal:2',
            'tax' => 'decimal:2',
            'image' => 'string',
            'status' => 'boolean'
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(PharmaceuticalForm::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(TeamProductPrice::class);
    }

}
