<?php

namespace App\Models;

use App\Models\Team;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Waste extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'user_id',
        'reciclable',
        'ordinario',
        'guardian',
        'bolsa_roja',
        'imagen',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'reciclable' => 'float',
        'ordinario' => 'float',
        'guardian' => 'float',
        'bolsa_roja' => 'float',
        'imagen' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
