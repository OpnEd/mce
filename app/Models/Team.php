<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

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
    ];

    /**
     * Relación: Un equipo tiene muchos usuarios.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
