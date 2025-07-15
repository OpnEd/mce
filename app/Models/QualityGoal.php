<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QualityGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'data'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function processes(): BelongsTo
    {
        return $this->belongsTo(Process::class);
    }

    public function managementIndicators(): HasMany
    {
        return $this->hasMany(ManagementIndicator::class);
    }
}
