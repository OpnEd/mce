<?php

namespace App\Models\Api;

use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExternalOrder extends Model
{
    protected $table = 'external_orders';
    
    protected $fillable = [
        'external_order_id',
        'external_created_at',
        'team_id',
        'status',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'customer_lat',
        'customer_lng',
        'notify_radius_m',
        'notes',
        'payment_method',
        'estimated_total',
        'payload',
        'claimed_at',
        'claimed_by',
    ];

    protected $casts = [
        'customer_lat' => 'float',
        'customer_lng' => 'float',
        'payload' => 'array',
        'claimed_at' => 'datetime',
    ];

    public function externalOrderTeamCandidates(): HasMany
    {
        return $this->hasMany(ExternalOrderTeamCandidate::class, 'external_order_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ExternalOrderItem::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class)->withDefault();
    }

    // ===== Reglas de dominio =====

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function canBeClaimedBy(Team $team): bool
    {
        return $this->isPending() && is_null($this->team_id);
    }

    public function claimBy(Team $team): void
    {
        $this->update([
            'team_id' => $team->id,
            'status' => 'claimed',
        ]);
    }

    // Scope: órdenes disponibles
    public function scopeAvailable($query)
    {
        return $query->whereNull('team_id');
    }

    // Scope: órdenes asignadas a un team
    public function scopeForTeam($query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }
}
