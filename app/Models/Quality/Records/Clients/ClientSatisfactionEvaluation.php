<?php

namespace App\Models\Quality\Records\Clients;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientSatisfactionEvaluation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'client_satisfaction_evaluations';

    protected $fillable = [
        'team_id',
        'user_id',
        'evaluated_at',
        'channel',
        'service_area',
        'is_anonymous',
        'client_name',
        'client_document',
        'client_phone',
        'client_email',
        'overall_score',
        'attention_score',
        'waiting_time_score',
        'availability_score',
        'information_clarity_score',
        'cleanliness_score',
        'facility_score',
        'would_recommend',
        'recommendation_score',
        'would_return',
        'comments',
        'follow_up_required',
    ];

    protected $casts = [
        'evaluated_at' => 'datetime',
        'is_anonymous' => 'boolean',
        'would_recommend' => 'boolean',
        'would_return' => 'boolean',
        'follow_up_required' => 'boolean',
    ];

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

    public static function getServiceAreas(): array
    {
        return [
            'dispensacion' => 'Dispensacion de medicamentos',
            'asesoria' => 'Asesoria farmacoterapeutica',
            'domicilio' => 'Entrega a domicilio',
            'posventa' => 'Posventa y garantias',
            'farmacovigilancia' => 'Farmacovigilancia',
            'otros' => 'Otros',
        ];
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
