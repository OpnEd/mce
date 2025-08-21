<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'drogueria_name',
        'empleados',
        'mayor_problema',
        'source',
        'utm_source',
        'utm_medium',
        'utm_campaign',
    ];
}
