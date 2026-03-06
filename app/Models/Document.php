<?php

namespace App\Models;

use App\Models\Quality\DocumentVersion;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_PREPARATION = 'preparation';
    public const STATUS_REVIEW = 'review';
    public const STATUS_APPROVED = 'approved';

    protected static ?HtmlSanitizer $richTextSanitizer = null;

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

    public function document_category(): BelongsTo
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

    public function setScopeAttribute(?string $value): void
    {
        $this->attributes['scope'] = self::sanitizeRichText($value);
    }

    public static function sanitizeRichText(?string $value): string
    {
        if (! is_string($value) || trim($value) === '') {
            return '';
        }

        return self::getRichTextSanitizer()->sanitize($value);
    }

    protected static function getRichTextSanitizer(): HtmlSanitizer
    {
        if (self::$richTextSanitizer !== null) {
            return self::$richTextSanitizer;
        }

        $config = (new HtmlSanitizerConfig())
            ->allowSafeElements()
            ->allowRelativeLinks();

        self::$richTextSanitizer = new HtmlSanitizer($config);

        return self::$richTextSanitizer;
    }

    public function getWorkflowStatusAttribute(): string
    {
        $submittedForReviewAt = data_get($this->data ?? [], 'submitted_for_review_at');

        if (! empty($this->approved_by)) {
            return self::STATUS_APPROVED;
        }

        if (! empty($this->reviewed_by) || ! empty($submittedForReviewAt)) {
            return self::STATUS_REVIEW;
        }

        return self::STATUS_PREPARATION;
    }

    public function isInPreparation(): bool
    {
        return $this->workflow_status === self::STATUS_PREPARATION;
    }

    public function isInReview(): bool
    {
        return $this->workflow_status === self::STATUS_REVIEW;
    }

    public function isApproved(): bool
    {
        return $this->workflow_status === self::STATUS_APPROVED;
    }
}
