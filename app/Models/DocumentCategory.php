<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id', //FK opcional para categorías específicas del inquilino (nullable si es una categoría general)
        'name',
        'code',
        'description',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
