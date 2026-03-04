<?php

namespace App\Filament\Pages;

use App\Models\MinutesIvcSectionEntry;
use App\Services\LinkResolver;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class BaseSectionPage extends Page
{
    public const NAVIGATION_LABEL = '1. Cédula del Establecimiento';
    public const NAVIGATION_GROUP = 'Secretaría de Salud';
    public const NAVIGATION_SORT = 1;
    public const SLUG = 'cedula-del-establecimiento';
    public const VIEW = 'filament.pages.base-section-page';
    public const SECTION = '';
    public const TITLE = '';

    public $team;
    public string $ownerName = '';
    public ?string $ownerCardType = '';
    public ?string $ownerCardNumber = '';
    public ?int $teamId = null;
    public array $entries = [];
    public string $section = '';
    public array $sectionEntries = [];

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function getNavigationLabel(): string
    {
        return static::NAVIGATION_LABEL;
    }

    public static function getNavigationGroup(): ?string
    {
        return static::NAVIGATION_GROUP;
    }

    public static function getNavigationSort(): ?int
    {
        return static::NAVIGATION_SORT;
    }

    public static function getSlug(): string
    {
        return static::SLUG;
    }

    public function getView(): string
    {
        return static::VIEW;
    }

    public function getTitle(): string|Htmlable
    {
        return static::TITLE !== '' ? static::TITLE : static::NAVIGATION_LABEL;
    }

    public function mount(LinkResolver $linkResolver): void
    {
        $this->section = static::SECTION;

        $this->team = Filament::getTenant();
        $this->teamId = $this->team->id;

        $owner = $this->team->users()->wherePivot('is_owner', true)->first();

        if ($owner) {
            $this->ownerName = $owner->name;
            $this->ownerCardType = $owner->card_type;
            $this->ownerCardNumber = $owner->card_number;
        }

        if (! $this->teamId) {
            $this->entries = [];

            return;
        }

        $entries = MinutesIvcSectionEntry::query()
            ->with('minutesIvcSection')
            ->whereHas('minutesIvcSection', function ($query) {
                $query->where('team_id', $this->teamId)
                    ->where('name', $this->section);
            })
            ->get()
            ->toArray();

        foreach ($entries as &$entry) {
            $entry['resolved_links'] = [];
            if (! empty($entry['links']) && is_array($entry['links'])) {
                $entry['resolved_links'] = $linkResolver->resolve(
                    $entry['links'],
                    $entry,
                    $this->teamId
                );
            }
        }

        $this->sectionEntries = $entries;
    }
}
