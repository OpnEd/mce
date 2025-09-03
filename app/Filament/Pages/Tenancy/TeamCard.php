<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\MinutesIvcSectionEntry;
use App\Models\TenantSetting;
use Filament\Pages\Page;
use App\Services\Tenancy\SettingsService;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Services\LinkResolver;

class TeamCard extends Page
{
    //protected static ?string $cluster = InspectionSurveillanceControlAudit::class;

    use InteractsWithForms;

    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.tenancy.team-card';
    protected static ?string $navigationLabel = '1. Cédula del Establecimiento';
    protected static ?string $navigationGroup = 'Secretaría de Salud';
    protected static ?string $slug = 'cedula-del-establecimiento';

    public $teamId;
    public $section = 'Cédula del establecimiento';    // grouped settings ready for rendering;
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
        
        //dd($minutesIvcSectionEntries);
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

    /*public $teamId;
    public $section = 'Cédula del establecimiento';    // grouped settings ready for rendering;
    public $entries = [];    // raw tenant_settings collection/array
    public $sectionEntries = [];
    public $groups = [];

    public function mount(SettingsService $settingsService): void
    {

        // obtener tenant de Filament (adapta si usas otro método)
        $this->teamId = Filament::getTenant()?->id ?? null;

        if (! $this->teamId) {
            $this->entries = [];
            return;
        }

        // Eager-load de la relación 'setting' para evitar N+1
        $tenantSettings = TenantSetting::with('setting')
            ->where('team_id', $this->teamId)
            ->orderBy('setting_id')
            ->get()
            ->toArray();

        // Conserva el array bruto si lo necesitas (por ejemplo para export)
        $this->groups = $settingsService->groupSettingsByGroup($tenantSettings);
    }*/
}
