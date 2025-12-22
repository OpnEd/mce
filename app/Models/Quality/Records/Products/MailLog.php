<?php

namespace App\Models\Quality\Records\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MailLog extends Model
{
    protected $fillable = [
        'team_id',
        'dispense_record_id',
        'email',
        'subject',
        'medication_key',
        'payload',
        'sent_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'sent_at' => 'datetime',
    ];

    public function dispenseRecord(): BelongsTo
    {
        return $this->belongsTo(DispenseRecord::class);
    }
}
