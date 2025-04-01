<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id',
        'title',
        'process_id',
        'document_category_id',
        'body',
        'slug',
        'records',
        'data',
    ];

    protected $casts = [
        'process_id' => 'integer',
        'document_type_id' => 'integer',
        'validity' => 'date',
        'records' => 'array',
        'data' => 'array',
    ];

    public function records(): HasMany
    {
        return $this->hasMany(Record::class);
    }

    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class);
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class);
    }
}
