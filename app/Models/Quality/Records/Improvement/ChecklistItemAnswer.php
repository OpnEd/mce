<?php

namespace App\Models\Quality\Records\Improvement;

//use App\Traits\FilterByTeam;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ChecklistItemAnswer extends Model
{
    use HasFactory
        //FilterByTeam
        ;

    protected $fillable = [
        'team_id',
        'user_id',
        'checklist_item_id',
        'meets',
        'apply',
        'evidence',
        'observations'
    ];

    protected $casts = [
        'meets' => 'boolean',
        'apply' => 'boolean',
        'evidence' => 'array',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function checklistItem(): BelongsTo
    {
        return $this->belongsTo(ChecklistItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function improvementPlan(): HasOne
    {
        return $this->hasOne(ImprovementPlan::class);
    }
}
