<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnesthesiaSheet extends Model
{
    /** @use HasFactory<\Database\Factories\AnesthesiaSheetFactory> */
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'team_id',
        'user_id',
        'recipe_number',
        'customer_id',
        'pet_id',
        'surgeon_id',
        'anamnesis',
        'anesthesia_notes',
        'anesthesia_start_time',
        'anesthesia_end_time',
        'status', // opened, closed
    ];

    protected $casts = [
        'anesthesia_notes' => 'array',
        'anamnesis' => 'array',
        'anesthesia_start_time' => 'datetime',
        'anesthesia_end_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function surgeon(): BelongsTo
    {
        return $this->belongsTo(User::class)
            ->where('is_surgeon', true);
    }
    
    public function anesthesiaItems(): HasMany
    {
        return $this->hasMany(AnesthesiaSheetItem::class);
    }
}
