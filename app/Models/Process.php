<?php

namespace App\Models;

use App\Models\Quality\ProcessTeam;
use App\Models\Quality\QualityGoal;
use App\Models\Quality\RiskAssessment\Risk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Process extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id',
        'process_type_id',
        'records',
        'code',
        'name',
        'slug',
        'description',
        'suppliers',
        'inputs',
        'procedures',
        'outputs',
        'clients',
        'data',
    ];

    protected $casts = [
        'records'    => 'array',
        'suppliers'  => 'array',
        'inputs'     => 'array',
        'procedures' => 'array',
        'outputs'    => 'array',
        'clients'    => 'array',
        'data'       => 'array',
    ];

    public function checklists(): HasMany
    {
        return $this->hasMany(Checklist::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function process_type(): BelongsTo
    {
        return $this->belongsTo(ProcessType::class);
    }

    public function records(): BelongsToMany
    {
        return $this->belongsToMany(Record::class);
    }
    
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function qualityGoals(): HasMany
    {
        return $this->hasMany(QualityGoal::class);
    }

    public function risks(): HasMany
    {
        return $this->hasMany(Risk::class);
    }
}
