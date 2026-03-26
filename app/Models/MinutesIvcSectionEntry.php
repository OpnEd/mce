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
    
    public const TEXT = 'text';
    public const UPLOAD = 'upload';
    public const ROUTE = 'route';
    public const FOLDER = 'folder';

    public static function values(): array
    {
        return [
            self::TEXT,
            self::UPLOAD,
            self::ROUTE,
            self::FOLDER,
        ];
    }

    public static function normalizeEntryType(mixed $entryType): string
    {
        $type = is_string($entryType) ? strtolower(trim($entryType)) : '';

        return match ($type) {
            self::UPLOAD, 'file' => self::UPLOAD,
            self::ROUTE => self::ROUTE,
            self::FOLDER => self::FOLDER,
            self::TEXT, 'boolean', 'select', '' => self::TEXT,
            default => self::TEXT,
        };
    }

    /**
     * Normalize links for Filament form state.
     * Supports both:
     * - associative format: ['document.slug' => 'manual']
     * - list format: [['key' => 'document.slug', 'value' => 'manual']]
     */
    public static function normalizeLinksForFormState(mixed $state): array
    {
        if (! is_array($state)) {
            return [];
        }

        $normalized = [];

        foreach ($state as $itemKey => $itemValue) {
            $key = '';
            $value = '';

            if (is_array($itemValue) && array_key_exists('key', $itemValue) && array_key_exists('value', $itemValue)) {
                $key = is_scalar($itemValue['key']) ? (string) $itemValue['key'] : '';
                $value = is_scalar($itemValue['value']) ? (string) $itemValue['value'] : '';
            } elseif (is_string($itemKey)) {
                $key = $itemKey;
                $value = is_scalar($itemValue) ? (string) $itemValue : '';
            }

            if ($key === '' && $value === '') {
                continue;
            }

            $normalized[] = [
                'key' => $key,
                'value' => $value,
            ];
        }

        return array_values($normalized);
    }

    /**
     * Normalize links before persisting to DB in list format.
     */
    public static function normalizeLinksForStorage(mixed $state): array
    {
        return self::normalizeLinksForFormState($state);
    }

    public function setEntryTypeAttribute(mixed $value): void
    {
        $this->attributes['entry_type'] = self::normalizeEntryType($value);
    }

    public function minutesIvcSection(): BelongsTo
    {
        return $this->belongsTo(MinutesIvcSection::class);
    }
}
