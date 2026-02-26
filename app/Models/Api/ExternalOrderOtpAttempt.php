<?php

namespace App\Models\Api;

use App\Models\Api\ExternalOrder;
use Illuminate\Database\Eloquent\Model;

class ExternalOrderOtpAttempt extends Model
{
    protected $fillable = [
        'external_order_id',
        'attempted_code',
        'success',
        'attempted_at',
    ];

    protected $casts = [
        'attempted_at' => 'datetime',
        'success' => 'boolean',
    ];

    const UPDATED_AT = null;

    public function order()
    {
        return $this->belongsTo(ExternalOrder::class, 'external_order_id');
    }
}
