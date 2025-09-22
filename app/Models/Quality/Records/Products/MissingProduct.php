<?php

namespace App\Models\Quality\Records\Products;

use App\Models\Product;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MissingProduct extends Model
{
    use HasFactory;

    protected $table = 'missing_products';

    protected $fillable = [
        'team_id',
        'user_id',
        'product_id',
        'type'
    ];

    public static function getTypes()
    {
        return [
            'faltante_ordinario' => 'Faltante Ordinario',
            'faltante_efectivo' => 'Faltante Efectivo',
            'faltante_baja_rotacion' => 'Faltante Baja Rotación',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
