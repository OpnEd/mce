<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id',
        'sale_id',
        'supplier_id',
        'code',
        'amount',
        'is_our', // el modelo Invoice almacena todas las facturas, incluidas las que vienen de terceros
        'issued_date',
        'data',
    ];

    protected $casts = [
        'issued_date' => 'datetime',
        'is_our' => 'boolean',
        'amount' => 'decimal:2',
        'data' => 'array',
    ];

    public function invoice(): HasOne
    {
        return $this->hasOne(ProductReception::class);
    }
    // Relación inversa con Venta
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

}
