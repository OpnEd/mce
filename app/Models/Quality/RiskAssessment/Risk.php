<?php

namespace App\Models\Quality\RiskAssessment;

use App\Models\Process;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Risk extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'team_id',
        'process_id',
        'owner_id',
        'code',
        'title',
        'activity',
        'description',
        'cause',
        'consequence',
        'risk_type',
        'impact_area',
        'existing_controls',
        'probability',
        'impact',
        'risk_score',
        'risk_level',
        'residual_probability',
        'residual_impact',
        'residual_score',
        'residual_level',
        'treatment_plan',
        'status',
        'review_at',
        'data',
    ];

    protected $casts = [
        'probability' => 'integer',
        'impact' => 'integer',
        'risk_score' => 'integer',
        'residual_probability' => 'integer',
        'residual_impact' => 'integer',
        'residual_score' => 'integer',
        'review_at' => 'date',
        'data' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function (Risk $risk): void {
            [$riskScore, $riskLevel] = $risk->calculateRisk($risk->probability, $risk->impact);
            $risk->risk_score = $riskScore;
            $risk->risk_level = $riskLevel;

            [$residualScore, $residualLevel] = $risk->calculateRisk($risk->residual_probability, $risk->residual_impact);
            $risk->residual_score = $residualScore;
            $risk->residual_level = $residualLevel;
        });
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function calculateRisk(?int $probability, ?int $impact): array
    {
        if (! $probability || ! $impact) {
            return [null, null];
        }

        $score = $probability * $impact;

        return [$score, $this->levelFromScore($score)];
    }

    public function levelFromScore(int $score): string
    {
        return match (true) {
            $score <= 4 => 'bajo',
            $score <= 9 => 'medio',
            $score <= 16 => 'alto',
            default => 'critico',
        };
    }

    public function riskLevelLabel(?string $level): string
    {
        return match ($level) {
            'bajo' => 'Bajo',
            'medio' => 'Medio',
            'alto' => 'Alto',
            'critico' => 'Critico',
            default => $level ?? '-',
        };
    }

    public function riskLevelColor(?string $level): string
    {
        return match ($level) {
            'bajo' => 'success',
            'medio' => 'warning',
            'alto' => 'danger',
            'critico' => 'primary',
            default => 'gray',
        };
    }
}
