<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    /** @use HasFactory<\Database\Factories\ManufacturerFactory> */
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que se asignarán de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'identification',
        'address',
        'email',
        'phonenumber',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function purchases()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
