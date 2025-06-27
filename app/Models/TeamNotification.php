<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification as BaseNotification;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Scopes\TeamNotificationScope;

class TeamNotification extends BaseNotification
{
    protected $table = 'notifications';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
        'team_id',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    protected static function booted()
    {
        /* static::creating(function (TeamNotification $notification) {
            // Asume que Filament::getTenant() devuelve el objeto tenant
            $notification->team_id = Filament::getTenant()?->id;
        }); */

        static::addGlobalScope(new TeamNotificationScope);

        static::creating(function ($notification) {

            // Asume que Filament::getTenant() devuelve el objeto tenant
            $notification->team_id = Filament::getTenant()?->id;

        });

        // Opcional: global scope para filtrar siempre por tenant
        static::addGlobalScope('team', function ($builder) {

            $team = Filament::getTenant();

            if ($team) {
                $builder->where('team_id', $team->id);
            }

        });
    }


}
