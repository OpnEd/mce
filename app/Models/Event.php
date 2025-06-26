<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $fillable = [
        'team_id',
        'user_id',
        'role_id',
        'schedule_id',
        'title',
        'description',
        'type', // 'event', 'task', 'milestone'
        'start_date',
        'end_date',
        'has_time',
        'start_time',
        'end_time',
        'done'
    ];

    protected $casts = [
        'done' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'has_time' => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];
    
    /**
     * Booted: auto-asigna el team_id del tenant actual al crear un evento.
     */
    protected static function booted()
    {
        static::creating(function (Event $event) {
            if (empty($event->team_id)) {
                $tenant = Filament::getTenant();
                $event->team_id = $tenant?->id;
            }
            if (empty($event->user_id)) {
                $event->user_id = Auth::user()->id;
            }
        });
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
