<?php

namespace App\Models\Quality\Records\Products;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_returns';

    public const DEFAULT_RESPONSE_DAYS_BY_TYPE = [
        'vencimiento' => 5,
        'defecto_calidad' => 7,
        'error_despacho' => 3,
        'retiro_sanitario' => 2,
        'otros' => 10,
    ];

    protected $fillable = [
        'team_id',
        'user_id',
        'supplier_id',
        'purchase_id',
        'received_at',
        'type',
        'priority',
        'status',
        'response_time_limit_days',
        'response_due_at',
        'return_code',
        'supplier_reference',
        'items',
        'reason',
        'observations',
        'total_items',
        'total_value',
        'supplier_response',
        'authorization_code',
        'credit_note_number',
        'responded_at',
        'closed_at',
        'requires_follow_up',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'response_due_at' => 'datetime',
        'responded_at' => 'datetime',
        'closed_at' => 'datetime',
        'items' => 'array',
        'requires_follow_up' => 'boolean',
    ];

    public static function getTypes(): array
    {
        return [
            'vencimiento' => 'Vencimiento',
            'defecto_calidad' => 'Defecto de calidad',
            'error_despacho' => 'Error de despacho',
            'retiro_sanitario' => 'Retiro sanitario',
            'otros' => 'Otros',
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
            'registrado' => 'Registrado',
            'enviado' => 'Enviado a proveedor',
            'en_revision' => 'En revision',
            'aprobado' => 'Aprobado',
            'rechazado' => 'Rechazado',
            'cerrado' => 'Cerrado',
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

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }
}
