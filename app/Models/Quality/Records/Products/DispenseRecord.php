<?php

namespace App\Models\Quality\Records\Products;

use App\Models\Team;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DispenseRecord extends Model
{
    protected $fillable = [
        'team_id',
        'client_name',
        'client_email',
        'client_phone',
        'medication_key',
        'notes',
        'sent_at',
    ];


    protected $casts = [
        'sent_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (DispenseRecord $dispenseRecord) {
            // Si no está explícito en $dispenseRecord->team_id
            if (empty($dispenseRecord->team_id)) {
                $tenant = Filament::getTenant();
                $dispenseRecord->team_id = $tenant ? $tenant->id : null;
            }
        });
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function mailLog(): HasOne
    {
        return $this->hasOne(MailLog::class);
    }
}
