<?php

// app/Models/LimpiezaDerrame.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpillCleanup extends Model
{
    use HasFactory;

    // Campos asignables en masa
    protected $fillable = [
        'fecha', 
        'hora', 
        'ubicacion', 
        'sustancia', 
        'tipo', 
        'cantidad', 
        'unidad', 
        'medidas_seguridad', 
        'personal_expuesto', 
        'acciones', 
        'observaciones',
        'team_id', // Asocia el registro a la droguería (multitenant)
        'user_id'  // Usuario que creó el registro
    ];

    // Si hay relación con droguería o usuario, se puede definir aquí:
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
