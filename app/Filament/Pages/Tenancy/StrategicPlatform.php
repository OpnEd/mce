<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Pages\Page;
use App\Models\Setting;
use App\Models\TenantSetting;
use Filament\Facades\Filament;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class StrategicPlatform extends Page
{

    protected static string $view = 'filament.pages.tenancy.strategic-platform';
    protected static ?string $slug = 'plataforma-estrategica';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public $tenant = null; // Team model (current tenant)

    // Datos preparados para la vista
    public $mission = null;
    public $vision = null;
    public $quality_policy = null;
    public $values = [];
    public $quality_objectives = [];
    public $management_indicators = [];

    public function mount(): void
    {
        // Detectar tenant: adapta según tu app si necesitas otro método
        $this->tenant = Filament::getTenant();

        $this->loadContent();
    }

    protected function loadContent(): void
    {
        $tenantId = $this->tenant?->id;

        // Lista de keys que buscaremos
        $keys = [
            'Misión',
            'Visión',
            'Política de Calidad',
            'Valores',
            'Objetivos de Calidad',
            'Indicadores de gestión',
        ];

        // Cargar settings globales que definen las keys
        $settings = Setting::whereIn('key', $keys)->get()->keyBy('key');

        // Para cada key intentamos leer TenantSetting (por tenant_id o team_id) y caer al value global
        foreach ($keys as $key) {
            $setting = $settings->get($key);
            $tenantValue = null;

            if ($setting && $tenantId) {
                $ts = TenantSetting::where('setting_id', $setting->id)
                    ->where(function ($q) use ($tenantId) {
                        $q->where('team_id', $tenantId);
                    })
                    ->latest('updated_at')
                    ->first();

                if ($ts) {
                    // Priorizar columna 'value' si existe, sino 'data' array
                    $tenantValue = $ts->value ?? Arr::get($ts->data ?? [], 'value');
                    // if data is full array and relevant, we can use it
                    if (is_null($tenantValue) && is_array($ts->data) && !empty($ts->data)) {
                        $tenantValue = $ts->data;
                    }
                }
            }

            // Fallback: valor global en settings.value
            $globalValue = $setting?->value;

            // Decide final value
            $final = $tenantValue ?? $globalValue ?? null;

            // Normalizar arrays: si el final es JSON/array, castearlo
            if (is_string($final)) {
                // intentamos decodificar JSON; si falla, dejamos la cadena
                $decoded = json_decode($final, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $final = $decoded;
                }
            }

            switch ($key) {
                case 'Misión':
                    $this->mission = $final;
                    break;
                case 'Visión':
                    $this->vision = $final;
                    break;
                case 'Política de Calidad':
                    $this->quality_policy = $final;
                    break;
                case 'Valores':
                    $this->values = is_array($final) ? $final : ($final ? preg_split('/\r?\n|\,/', (string) $final) : []);
                    break;
                case 'Objetivos de Calidad':
                    $this->quality_objectives = is_array($final) ? $final : ($final ? preg_split('/\r?\n|\,/', (string) $final) : []);
                    break;
                case 'Indicadores de gestión':
                    $this->management_indicators = is_array($final) ? $final : ($final ? preg_split('/\r?\n|\,/', (string) $final) : []);
                    break;
            }
        }
    }



}
