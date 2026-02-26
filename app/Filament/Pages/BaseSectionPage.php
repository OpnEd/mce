<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\MinutesIvcSectionEntry;
use App\Services\LinkResolver;
use Filament\Facades\Filament;

class BaseSectionPage extends Page
{
    public ?int $teamId = null;
    public array $entries = [];
    public string $section = '';
    public array $sectionEntries = [];
    protected static string $view = 'filament.pages.base-section-page';
    
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount(LinkResolver $linkResolver): void
    {
        // obtener tenant de Filament (adapta si usas otro método)
        $this->teamId = Filament::getTenant()?->id ?? null;

        if (! $this->teamId) {
            $this->entries = [];
            return;
        }

        // Eager-load de la relación 'setting' para evitar N+1
        //$tenantSettings = TenantSetting::with('setting')
        $entries = MinutesIvcSectionEntry::query()
            ->with('minutesIvcSection')
            ->whereHas('minutesIvcSection', function ($query) {
                $query->where('team_id', $this->teamId)
                      ->where('name', $this->section);
            })
            ->get()
            ->toArray();

        // Procesar links usando LinkResolver
        foreach ($entries as &$entry) {
            $entry['resolved_links'] = [];
            if (!empty($entry['links']) && is_array($entry['links'])) {
                $entry['resolved_links'] = $linkResolver->resolve(
                    $entry['links'],
                    $entry,
                    $this->teamId
                    );
            }
        }

        // Conserva el array bruto si lo necesitas (por ejemplo para export)
        $this->sectionEntries = $entries;
    }
}
