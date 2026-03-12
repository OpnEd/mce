<?php

namespace App\Models\Quality;

use App\Models\ManagementIndicator;
use App\Models\Process;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QualityGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'process_id',
        'name',
        'description',
    ];

    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class);
    }

    public function managementIndicators(): HasMany
    {
        return $this->hasMany(ManagementIndicator::class);
    }
    // Relación uno a muchos con Team
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

}
