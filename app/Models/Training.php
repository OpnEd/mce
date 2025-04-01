<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'training_category_id',
        'title',
        'content',
        'video',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function training_category(): BelongsTo
    {
        return $this->belongsTo(TrainingCategory::class);
    }

/*     public function evaluationrecord(): HasMany
    {
        return $this->hasMany(EvaluationRecord::class);
    } */
}
