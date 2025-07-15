<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MinutesIvcSection extends Model
{
    protected $fillable = [
        'team_id',
        'name',
        'description',
        'slug',
        'order',
        'status',
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(MinutesIvcSectionEntry::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

}
