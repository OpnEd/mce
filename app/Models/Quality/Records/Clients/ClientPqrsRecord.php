<?php

namespace App\Models\Quality\Records\Clients;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientPqrsRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'client_pqrs_records';

    public const DEFAULT_RESPONSE_DAYS_BY_TYPE = [
        'peticion' => 15,
        'queja' => 8,
        'reclamo' => 10,
        'sugerencia' => 15,
    ];

    protected $fillable = [
        'team_id',
        'user_id',
        'received_at',
        'channel',
        'type',
        'priority',
        'status',
        'response_time_limit_days',
        'response_due_at',
        'tracking_code',
        'subject',
        'description',
        'client_name',
        'client_document',
        'client_phone',
        'client_email',
        'is_anonymous',
        'responsible_area',
        'response',
        'responded_at',
        'corrective_action',
        'closed_at',
        'requires_follow_up',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'response_due_at' => 'datetime',
        'responded_at' => 'datetime',
        'closed_at' => 'datetime',
        'is_anonymous' => 'boolean',
        'requires_follow_up' => 'boolean',
    ];

    public static function getTypes(): array
    {
        return [
            'peticion' => 'Peticion',
            'queja' => 'Queja',
            'reclamo' => 'Reclamo',
            'sugerencia' => 'Sugerencia',
        ];
    }

    public static function getPriorities(): array
    {
        return [
            'baja' => 'Baja',
            'media' => 'Media',
            'alta' => 'Alta',
            'critica' => 'Critica',
        ];
    }

    public static function getStatuses(): array
    {
        return [
            'recibido' => 'Recibido',
            'en_analisis' => 'En analisis',
            'respondido' => 'Respondido',
            'cerrado' => 'Cerrado',
        ];
    }

    public static function getChannels(): array
    {
        return [
            'presencial' => 'Presencial',
            'telefonico' => 'Telefonico',
            'digital' => 'Digital',
            'domicilio' => 'Domicilio',
            'otro' => 'Otro',
        ];
    }

    public static function getDefaultResponseDaysByType(?string $type): ?int
    {
        if (! $type) {
            return null;
        }

        return self::DEFAULT_RESPONSE_DAYS_BY_TYPE[$type] ?? null;
    }

    public function getIsOverdueAttribute(): bool
    {
        if (! $this->response_due_at) {
            return false;
        }

        if ($this->status === 'cerrado' || $this->responded_at) {
            return false;
        }

        return now()->greaterThan($this->response_due_at);
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
