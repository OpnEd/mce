<?php

namespace Tests\Unit;

use App\Models\MinutesIvcSectionEntry;
use PHPUnit\Framework\TestCase;

class MinutesIvcSectionEntryTest extends TestCase
{
    public function test_normalize_entry_type_maps_legacy_values(): void
    {
        $this->assertSame(MinutesIvcSectionEntry::TEXT, MinutesIvcSectionEntry::normalizeEntryType('boolean'));
        $this->assertSame(MinutesIvcSectionEntry::UPLOAD, MinutesIvcSectionEntry::normalizeEntryType('file'));
        $this->assertSame(MinutesIvcSectionEntry::TEXT, MinutesIvcSectionEntry::normalizeEntryType('select'));
    }

    public function test_entry_type_mutator_normalizes_values(): void
    {
        $entry = new MinutesIvcSectionEntry();
        $entry->entry_type = 'file';

        $this->assertSame(MinutesIvcSectionEntry::UPLOAD, $entry->entry_type);
    }
}
