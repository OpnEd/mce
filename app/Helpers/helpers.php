<?php

use App\Models\Setting;

if(!function_exists('getSettings'))
{
    function getSettings(array $columns, $tenantId)
    {
        $finalResult = [];
        $settings = Setting::whereIn('key', $columns)
            ->with(['settings' => function($query) use ($tenantId) {
                $query->where('team_id', $tenantId);
            }])
            ->get();
        foreach ($columns as $column) {
            $setting = $settings->firstWhere('key', $column);

            if ($setting) {
                $tenantSetting = $setting->settings->first();
                if($setting->type == "file")
                {
                    $finalResult[$column] = $tenantSetting ? asset($tenantSetting->value) : asset($setting->value);
                }
                else
                {
                    $finalResult[$column] = $tenantSetting ? $tenantSetting->value : $setting->value;
                }
            } else {
                $finalResult[$column] = '';
            }
        }
        return $finalResult;
    }
}

if (! function_exists('minutesIvcSectionDescriptionFromConfig')) {
    function minutesIvcSectionDescriptionFromConfig(array|string|int|null $identifiers, string $default = ''): string
    {
        $sections = config('minutes-ivc-sections', []);
        if (! is_array($sections) || empty($sections)) {
            return $default;
        }

        $values = is_array($identifiers) ? $identifiers : [$identifiers];

        // Alias para nombres/slug historicos usados en UI.
        $aliases = [
            'Recurso Humano' => 'Talento Humano',
            'recurso-humano' => 'talento-humano',
        ];

        foreach ($values as $value) {
            $candidates = [$value];
            if (is_string($value) && isset($aliases[$value])) {
                $candidates[] = $aliases[$value];
            }

            foreach ($candidates as $candidate) {
                foreach ($sections as $section) {
                    if (! is_array($section)) {
                        continue;
                    }

                    if (
                        ($section['name'] ?? null) === $candidate ||
                        ($section['slug'] ?? null) === $candidate ||
                        ($section['route'] ?? null) === $candidate ||
                        (isset($section['order']) && (string) $section['order'] === (string) $candidate)
                    ) {
                        return (string) ($section['description'] ?? $default);
                    }
                }
            }
        }

        return $default;
    }
}
