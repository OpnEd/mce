<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
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
        'team_id',
        'purchase_id',
        'product_id',
        'quantity',
        'price',
        'total',
        'enlisted',
        'type'
    ];

    protected $casts = [
        'enlisted' => 'boolean',
        'total' => 'decimal:2',
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    protected static function booted()
    {
        static::addGlobalScope('team', function (Builder $builder) {
            if (\Illuminate\Support\Facades\Auth::check()) {
                $builder->where('purchase_items.team_id', Filament::getTenant()?->id);
            }
        });

        static::creating(function (PurchaseItem $item) {
            if (empty($item->team_id)) {
                if ($item->purchase && isset($item->purchase->team_id)) {
                    $item->team_id = $item->purchase->team_id;
                } else {
                    $item->team_id = auth()->user()?->team_id ?? null;
                }
            }
        });

        // También podrías evitar cambios de team en updating:
        static::updating(function (PurchaseItem $item) {
            if ($item->isDirty('team_id')) {
                // revertir el cambio o lanzar excepción según tu política
                $item->team_id = $item->getOriginal('team_id');
            }
        });
    }

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
