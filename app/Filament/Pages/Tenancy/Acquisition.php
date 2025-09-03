<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Clusters\InspectionSurveillanceControlAudit;
use App\Models\MinutesIvcSectionEntry;
use App\Services\LinkResolver;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class Acquisition extends Page
{
    //protected static ?string $cluster = InspectionSurveillanceControlAudit::class;
    protected static ?int $navigationSort = 32;
    protected static ?string $navigationLabel = '11. Adquisición';
    protected static ?string $navigationGroup = 'Secretaría de Salud';

    protected static string $view = 'filament.pages.tenancy.acquisition';
    protected static ?string $slug = 'adquisicion';

    public $teamId;
    public $section = ' Proceso de Adquisición';    // grouped settings ready for rendering;
    public $entries = [];    // raw tenant_settings collection/array
    public $sectionEntries = [];

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
        $minutesIvcSectionEntries = MinutesIvcSectionEntry::with('minutesIvcSection')
            ->whereHas('minutesIvcSection', function ($query) {
                $query->where('team_id', $this->teamId)
                    ->where('name', $this->section);
            })
            ->get()
            ->toArray();

        // Procesar links usando LinkResolver
        foreach ($minutesIvcSectionEntries as &$entry) {
            $entry['resolved_links'] = [];
            if (!empty($entry['links']) && is_array($entry['links'])) {
                $entry['resolved_links'] = $linkResolver->resolve($entry['links'], $entry, $this->teamId);
            }
        }

        // Conserva el array bruto si lo necesitas (por ejemplo para export)
        $this->sectionEntries = $minutesIvcSectionEntries;
    }
}
