<?php

namespace App\Models\Api;

use App\Models\Api\ExternalOrder;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalOrderTeamCandidate extends Model
{
    use HasFactory;
    
    protected $table = 'external_order_team_candidates';

    protected $fillable = [
        'external_order_id',
        'team_id',
        'distance_km',
        'distance_m',
        'status',
        'notified_at',
    ];

    protected $casts = [
        'distance_km' => 'float',
        'distance_m' => 'integer',
        'notified_at' => 'datetime',
        'accepted_at' => 'datetime',
        'declined_at' => 'datetime',
    ];

    public function externalOrder(): BelongsTo
    {
        return $this->belongsTo(ExternalOrder::class, 'external_order_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
