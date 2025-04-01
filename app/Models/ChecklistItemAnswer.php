<?php

namespace App\Models;

//use App\Traits\FilterByTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function checklist_item(): BelongsTo
    {
        return $this->belongsTo(ChecklistItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
