<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SanitaryRegistry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'cum',
    ];

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }
}
