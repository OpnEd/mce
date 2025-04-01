<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'url',
        'icon',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class);
    }

    public function processes(): BelongsToMany
    {
        return $this->belongsToMany(Process::class);
    }
}
