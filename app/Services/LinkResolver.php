<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LinkResolver
{
    /**
     * Resuelve un array de links para una entrada dada.
     *
     * @param array $links  Array de links: [['key'=>'document.slug','value'=>'document.details','label'=>'Ver'], ...]
     * @param array $entry  Array que representa la entrada (p.e. MinutesIvcSectionEntry->toArray())
     * @param int   $teamId id del team/tenant
     * @return array Array de items resueltos con estructura:
     *               [
     *                 ['type'=>'route', 'url'=>'...', 'label'=>'...', 'route'=>'document.details', 'params'=>[...] , 'raw'=>[...] ],
     *                 ['type'=>'folder', 'text'=>'Archivo ubicado en ...', 'raw'=>[...] ],
     *               ]
     */
    public function resolve(array $links, array $entry, int $teamId): array
    {
        $out = [];

        foreach ($links as $key => $value) {
            // No necesitas comprobar isset($link['key'], $link['value'])
            // $key es el 'document.slug', $value es la ruta

            $label = 'Ver'; // O puedes construirlo a partir del key si lo deseas

            // 1) document.* -> route($value, ['tenant' => $teamId, 'document' => $slug])
            if (Str::startsWith($key, 'document.')) {
                $slug = Str::after($key, 'document.');
                if (!empty($slug)) {
                    try {
                        $params = ['tenant' => $teamId, 'document' => $slug];
                        $url = route($value, $params);
                        $out[] = [
                            'type' => 'route',
                            'url' => $url,
                            'label' => $slug,
                            'route' => $value,
                            'params' => $params,
                            'raw' => ['key' => $key, 'value' => $value],
                        ];
                    } catch (\Throwable $e) {
                        Log::warning("No se pudo generar route '{$value}' para document (document.*)", [
                            'error' => $e->getMessage(),
                            'key' => $key,
                            'team_id' => $teamId,
                            'resolved_slug' => $slug,
                        ]);
                    }
                } else {
                    Log::warning('No se encontró slug para link tipo document', ['key' => $key]);
                }
                continue;
            }

            // 2) record.* -> value es ruta de Filament; route($value, ['tenant' => $teamId])
            if (Str::startsWith($key, 'record.')) {
                $label = Str::after($key, 'record.');
                try {
                    $params = ['tenant' => $teamId]; // según tu instrucción para este caso
                    $url = route($value, $params);
                    $out[] = [
                        'type' => 'route',
                        'url' => $url,
                        'label' => $label,
                        'route' => $value,
                        'params' => $params,
                        'raw' => ['key' => $key, 'value' => $value],
                    ];
                } catch (\Throwable $e) {
                    Log::warning("No se pudo generar route '{$value}' para record.*", [
                        'error' => $e->getMessage(),
                        'key' => $key,
                        'team_id' => $teamId,
                    ]);
                }
                continue;
            }

            // 3) folder.* -> value es texto -> mostrar párrafo "Archivo ubicado en $value"
            if (Str::startsWith($key, 'folder.')) {
                $section= Str::after($key, 'folder.');
                $text = (string) $value;
                $out[] = [
                    'type' => 'folder',
                    'text' => "Archivo ubicado en la sección {$section}, {$text}",
                    'raw' => ['key' => $key, 'value' => $value],
                ];
                continue;
            }
        }

        return $out;
    }

    /**
     * Extrae un campo del array $entry soportando dot-notation (p.e. 'minutes_ivc_section.slug').
     * Retorna string|null
     */
    protected function getFieldFromEntry(array $entry, string $field): ?string
    {
        if ($field === '') return null;
        $parts = explode('.', $field);
        $current = $entry;
        foreach ($parts as $p) {
            if (is_array($current) && array_key_exists($p, $current)) {
                $current = $current[$p];
            } else {
                return null;
            }
        }
        return is_scalar($current) ? (string) $current : null;
    }
}
