<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamProductPrice extends Model
{
    protected $fillable = [
        'team_id',
        'product_id',
        'min', //stock mínimo
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    // Relación inversa: cada precio pertenece a un producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relación inversa: cada precio pertenece a un tenant
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

}
