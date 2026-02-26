<?php

namespace App\Models\Api;

use App\Models\Api\ExternalOrderOtpAttempt;
use App\Models\Sale;
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
        'otp_code',
        'otp_generated_at',
        'delivered_at',
        'delivery_code_input',
    ];

    protected $casts = [
        'customer_lat' => 'float',
        'customer_lng' => 'float',
        'payload' => 'array',
        'claimed_at' => 'datetime',
        'otp_generated_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function candidates(): HasMany
    {
        return $this->hasMany(ExternalOrderTeamCandidate::class, 'external_order_id');
    }

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
        return $this->status === 'PENDING';
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

    public function sale()
    {
        return $this->hasOne(Sale::class, 'external_order_id');
    }

    public function otpAttempts(): HasMany
    {
        return $this->hasMany(ExternalOrderOtpAttempt::class);
    }

    // Scope: órdenes asignadas a un team
    public function scopeForTeam($query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Helpers
    public function isAssigned(): bool
    {
        return $this->team_id !== null;
    }

    public function isInPreparation(): bool
    {
        return $this->status === 'CLAIMED';
    }

    public function isReadyToDispatch(): bool
    {
        return $this->status === 'CLAIMED' && $this->otp_code;
    }

    public function isDelivered(): bool
    {
        return $this->status === 'DELIVERED';
    }

    public function generateOtp(): string
    {
        $code = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $this->update([
            'otp_code' => $code,
            'otp_generated_at' => now(),
        ]);

        return $code;
    }

    /**
     * Verifica el código OTP ingresado contra el almacenado.
     * 
     * Solo valida el código, no realiza transiciones de estado.
     * El servicio ExternalOrderActionService es responsable de cambiar el status.
     * 
     * @param string $inputCode Código OTP a verificar
     * @return bool True si el código es correcto, false en caso contrario
     */
    public function verifyOtp(string $inputCode): bool
    {
        $attempt = $this->otpAttempts()->create([
            'attempted_code' => $inputCode,
            'success' => false,
        ]);

        if ($inputCode === $this->otp_code) {
            $attempt->update(['success' => true]);
            // Registrar que el código fue ingresado correctamente
            $this->update([
                'delivery_code_input' => $inputCode,
                'delivered_at' => now(),
            ]);

            return true;
        }

        return false;
    }
}
