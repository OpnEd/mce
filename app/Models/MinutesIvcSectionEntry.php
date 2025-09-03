<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MinutesIvcSectionEntry extends Model
{

    use SoftDeletes;
    
    protected $fillable = [
        'team_id',
        'minutes_ivc_section_id',
        'apply', 
        'entry_id',
        'criticality', //Critical, Major, Minor
        'question',
        'answer',
        'entry_type',//informativo, evidencia
        'links',
        'compliance',
    ];

    protected $casts = [
        'links' => 'array',
        'compliance' => 'boolean',
    ];

    public function minutesIvcSection(): BelongsTo
    {
        return $this->belongsTo(MinutesIvcSection::class);
    }
}
