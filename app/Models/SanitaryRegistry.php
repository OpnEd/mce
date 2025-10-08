<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SanitaryRegistry extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'code',
        'cum',
    ];
}
