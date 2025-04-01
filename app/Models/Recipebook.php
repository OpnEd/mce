<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipebook extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'consecutive',
        'issue_date',
        'team_id',
        'user_id', // Médico que diligencia
        'customer_id', // Propietario de mascota
        'patient_id', // Paciente o mascota
        'diagnosis',
        'signature',
    ];

    public function recipebook_items(): HasMany
    {
        return $this->hasMany(RecipebookItem::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
