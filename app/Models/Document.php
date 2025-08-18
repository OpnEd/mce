<?php

namespace App\Models;

use App\Models\Quality\DocumentVersion;
use Carbon\Carbon;
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
        'sequence',
        'process_id',
        'document_category_id',
        'objective',
        'scope',
        'references',
        'terms',
        'responsibilities',
        'procedure',
        'slug',
        'records',
        'annexes',
        'data',
        'prepared_by',
        'reviewed_by',
        'approved_by',
    ];

    protected $casts = [
        'process_id' => 'integer',
        'document_type_id' => 'integer',
        'records' => 'array',
        'data' => 'array',
        'references' => 'array',
        'terms' => 'array',
        'responsibilities' => 'array',
        'procedure' => 'array',
        'annexes' => 'array',
    ];

    protected $with = [
        'preparedBy.roles',
        'reviewedBy.roles',
        'approvedBy.roles',
    ];

    protected static function booted()
    {
        static::creating(function ($doc) {
            $max = static::where('team_id', $doc->team_id)
                ->max('sequence') ?: 0;
            $doc->sequence = $max + 1;
        });
    }

    /**
     * Devuelve el sequence con dos dígitos (01, 02, ...)
     */
    public function getSequencePaddedAttribute(): string
    {
        // 2 dígitos: ajusta el 2 si quieres más ceros
        return sprintf('%02d', $this->sequence);
    }

    /**
     * Devuelve la fecha de Vigencia en dd-mm-aaaa
     */
    public function getVigenciaFormattedAttribute(): ?string
    {
        if (! isset($this->data['Vigencia'])) {
            return null;
        }

        return Carbon::parse($this->data['Vigencia'])
            ->format('d-m-Y');
    }

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
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class)->latest();
    }


    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
