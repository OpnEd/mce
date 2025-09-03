<?php

namespace App\Services\Tenancy;

use Illuminate\Support\Str;

class SettingsService
{
    /**
     * Agrupa tenant settings por el campo 'group' del modelo Setting.
     *
     * @param \Illuminate\Support\Collection|array $tenantSettings
     * @return array
     */
    
    public function groupSettingsByGroup($tenantSettings): array
    {
        $groups = [];

        // Acepta collection de modelos o array (por seguridad)
        $collection = is_array($tenantSettings) ? collect($tenantSettings) : $tenantSettings;

        foreach ($collection as $ts) {
            // Manejo si $ts es un modelo Eloquent o un array (desde toArray)
            if ($ts instanceof \Illuminate\Database\Eloquent\Model) {
                $setting = $ts->setting; // relación belongsTo Setting
                $value = data_get($ts, 'data.value') ?? data_get($ts, 'value') ?? null;
            } else {
                // array
                $setting = $ts['setting'] ?? null;
                $value = data_get($ts, 'data.value') ?? ($ts['value'] ?? null);
            }

            // Si no existe la relación Setting, saltamos el registro (o lo ponemos en 'default')
            if (! $setting) {
                $groupName = 'default';
                $key = data_get($ts, 'setting.key') ?? null;
                $attributes = [];
                $type = 'text';
                $label = Str::headline($key ?? 'sin_key');
            } else {
                // Extraer datos de Setting (soportando modelo o array)
                if ($setting instanceof \Illuminate\Database\Eloquent\Model) {
                    $groupName = $setting->group ?? 'default';
                    $key = $setting->key ?? null;
                    $attributes = is_array($setting->attributes ?? null)
                        ? $setting->attributes
                        : (is_string($setting->attributes ?? '') ? json_decode($setting->attributes, true) ?? [] : []);
                    $type = $setting->type ?? 'text';
                    $label = $attributes['label'] ?? Str::headline($key ?? '');
                } else {
                    $groupName = $setting['group'] ?? 'default';
                    $key = $setting['key'] ?? null;
                    $attributes = is_array($setting['attributes'] ?? null)
                        ? $setting['attributes']
                        : (is_string($setting['attributes'] ?? '') ? json_decode($setting['attributes'], true) ?? [] : []);
                    $type = $setting['type'] ?? 'text';
                    $label = $attributes['label'] ?? Str::headline($key ?? '');
                }
            }

            // Inicializa el grupo si no existe
            if (! isset($groups[$groupName])) {
                $groups[$groupName] = [];
            }

            // Agrega el registro transformado
            $groups[$groupName][] = [
                'key' => $key,
                'label' => $label,
                'type' => $type,
                'value' => $value,
                'attributes' => $attributes,
                // opcionales: puedes incluir timestamps o el id del tenant_setting
                'tenant_setting_id' => is_array($ts) ? ($ts['id'] ?? null) : ($ts->id ?? null),
                'raw' => is_array($ts) ? $ts : $ts->toArray(),
            ];
        }

        return $groups;
    }
}
