<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', //categoría del producto
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

    public function product_category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function pharmaceutical_form(): BelongsTo
    {
        return $this->belongsTo(PharmaceuticalForm::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(TeamProductPrice::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * Scope: productos con stock total > 0
     * Lo que hace este scope es agregar un 
     * WHERE EXISTS (SELECT 1 FROM stocks … HAVING SUM(quantity)>0) 
     * al query de productos.
     */
    public function scopeInStock(Builder $query): Builder
    {
        return $query->whereExists(function ($subquery) {
            $subquery->from('stocks')
                ->select(DB::raw('1')) // importante: NO SELECT *
                ->whereColumn('stocks.product_id', 'products.id')
                ->groupBy('product_id')
                ->havingRaw('SUM(quantity) > 0');
        });
        // O también se puede hacer así:
        /* return $query->whereIn('id', function ($subquery) {
            $subquery->from('stocks')
                ->select('product_id')
                ->groupBy('product_id')
                ->havingRaw('SUM(quantity) > 0');
        }); */
    }
}
