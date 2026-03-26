<?php

namespace App\Services;

use App\Models\Schedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LinkResolver
{
    /**
     * Legacy -> canonical slugs for document links.
     *
     * @var array<string, string>
     */
    private const DOCUMENT_SLUG_ALIASES = [
        'control-integral-de-plagas' => 'control-integral-plagas',
        'plan-de-contingencia-para-el-suministro-de-agua-potable' => 'plan-contingencia-suministro-agua-potable',
        'adquisicion-de-medicamentos-y-dispositivos-medicos' => 'adquisicion-de-medicamentos-y-dispositivos-medicos',
        'almacenamiento-de-medicamentos-y-dispositivos-medicos' => 'almacenamiento-de-medicamentos-y-dispositivos-medicos',
        'atencion-pqrs' => 'atencion-pqrs',
        'auditoria-interna' => 'auditoria-interna',
        'control-integral-plagas' => 'control-integral-plagas',
        'delegacion-funciones-dt' => 'delegacion-funciones-dt',
        'devolucion-de-medicamentos-y-dispositivos-medicos' => 'devolucion-de-medicamentos-y-dispositivos-medicos',
        'dispensacion-de-medicamentos-y-dispositivos-medicos' => 'dispensacion-de-medicamentos-y-dispositivos-medicos',
        'evaluacion-gestion-riesgos' => 'evaluacion-gestion-riesgos',
        'gestion-de-devoluciones' => 'gestion-de-devoluciones',
        'gestion-documental' => 'gestion-documental',
        'induccion-capacitacion' => 'induccion-capacitacion',
        'limpieza-sanitazacion-areas' => 'limpieza-sanitazacion-areas',
        'manejo-productos-refrigerados' => 'manejo-productos-refrigerados',
        'farmacovigilancia' => 'farmacovigilancia',
        'medicion-satisfaccion-usuario' => 'medicion-satisfaccion-usuario',
        'manual-de-funciones' => 'manual-de-funciones',
        'manual-funciones-dt' => 'manual-funciones-dt',
        'matriz-riesgos' => 'matriz-riesgos',
        'mapa-de-procesos' => 'mapa-de-procesos',
        'planeacion-estrategica' => 'planeacion-estrategica',
    ];

    private const CHARACTERIZATION_SLUG_ALIASES = [
        'seleccion' => 'seleccion',
        'adquisicion' => 'adquisicion',
        'recepcion' => 'recepcion',
        'almacenamiento' => 'almacenamiento',
        'dispensacion' => 'dispensacion',
        'devolucion' => 'devolucion',
    ];

    /**
     * Resolve links for an entry.
     *
     * Supported input formats:
     * 1) Associative: ['folder.section' => 'tag', 'record.name' => 'route.name']
     * 2) Legacy list: [['key' => 'folder.section', 'value' => 'tag']]
     */
    public function resolve(array $links, array $entry, int $teamId): array
    {
        $out = [];
        $entryType = (string) ($entry['entry_type'] ?? '');

        foreach ($links as $key => $value) {
            [$linkKey, $linkValue, $label] = $this->normalizeLinkPair($key, $value);

            if ($linkKey === null || $linkKey === '') {
                continue;
            }

            if ($linkKey === 'document.slug') {
                $slug = self::normalizeDocumentSlug(trim($linkValue));

                if ($slug === '') {
                    Log::warning('Missing slug for document.slug link', ['key' => $linkKey]);
                    continue;
                }

                $resolved = $this->buildRouteLink(
                    'document.details',
                    ['tenant' => $teamId, 'document' => $slug],
                    $this->normalizeLinkLabel($label, 'Ver documento'),
                    $linkKey,
                    $linkValue,
                    $teamId
                );

                if ($resolved !== null) {
                    $out[] = $resolved;
                }

                continue;
            }

            if ($linkKey === 'characterization.slug') {
                $slug = self::normalizeProcessSlug(trim($linkValue));

                if ($slug === '') {
                    Log::warning('Missing slug for characterization.slug link', ['key' => $linkKey]);
                    continue;
                }

                $resolved = $this->buildRouteLink(
                    'generate.characterization',
                    ['tenant' => $teamId, 'process' => $slug],
                    $this->normalizeLinkLabel($label, 'Ver caracterización'),
                    $linkKey,
                    $linkValue,
                    $teamId
                );

                if ($resolved !== null) {
                    $out[] = $resolved;
                }

                continue;
            }

            if ($linkKey === 'page.route' || $linkKey === 'record.route') {
                $routeName = trim($linkValue);

                if ($routeName === '') {
                    Log::warning("Missing route name for {$linkKey}", ['key' => $linkKey]);
                    continue;
                }

                $defaultLabel = $linkKey === 'page.route' ? 'Ver pagina' : 'Ver registro';
                $resolved = $this->buildRouteLink(
                    $routeName,
                    ['tenant' => $teamId],
                    $this->normalizeLinkLabel($label, $defaultLabel),
                    $linkKey,
                    $linkValue,
                    $teamId
                );

                if ($resolved !== null) {
                    $out[] = $resolved;
                }

                continue;
            }

            if ($linkKey === 'matriz.riesgos') {
                $routeName = trim($linkValue);

                if ($routeName === '') {
                    Log::warning('Missing route name for matriz.riesgos', ['key' => $linkKey]);
                    continue;
                }

                $resolved = $this->buildRouteLink(
                    $routeName,
                    ['tenant' => $teamId],
                    $this->normalizeLinkLabel($label, 'Ver matriz de riesgos'),
                    $linkKey,
                    $linkValue,
                    $teamId
                );

                if ($resolved !== null) {
                    $out[] = $resolved;
                }

                continue;
            }

            if ($linkKey === 'schedule.route') {
                $resolved = $this->buildScheduleLink(
                    trim($linkValue),
                    $this->normalizeLinkLabel($label, 'Ver cronograma'),
                    $linkKey,
                    $linkValue,
                    $teamId
                );

                if ($resolved !== null) {
                    $out[] = $resolved;
                }

                continue;
            }

            // Legacy format: document.{slug} => route.name
            if (Str::startsWith($linkKey, 'document.') && $linkKey !== 'document.slug') {
                $slug = self::normalizeDocumentSlug(Str::after($linkKey, 'document.'));
                $routeName = trim($linkValue) !== '' ? trim($linkValue) : 'document.details';

                if ($slug === '') {
                    Log::warning('Missing slug for legacy document.* link', ['key' => $linkKey]);
                    continue;
                }

                $resolved = $this->buildRouteLink(
                    $routeName,
                    ['tenant' => $teamId, 'document' => $slug],
                    $this->normalizeLinkLabel($label, 'Ver documento'),
                    $linkKey,
                    $linkValue,
                    $teamId
                );

                if ($resolved !== null) {
                    $out[] = $resolved;
                }

                continue;
            }

            // Keep folder metadata available for the view message.
            if ($entryType === 'folder' || Str::startsWith($linkKey, 'folder.')) {
                $section = Str::startsWith($linkKey, 'folder.')
                    ? Str::after($linkKey, 'folder.')
                    : $this->inferSectionFromEntryId((string) ($entry['entry_id'] ?? ''));

                $out[] = [
                    'type' => 'folder',
                    'section' => $section,
                    'key' => $linkKey,
                    'value' => (string) $linkValue,
                    'raw' => ['key' => $linkKey, 'value' => $linkValue],
                ];

                continue;
            }

            // Keep upload metadata available for URL building in the view.
            if ($entryType === 'upload') {
                $out[] = [
                    'type' => 'upload',
                    'key' => $linkKey,
                    'value' => (string) $linkValue,
                    'raw' => ['key' => $linkKey, 'value' => $linkValue],
                ];

                continue;
            }
        }

        return $out;
    }

    private function normalizeLinkPair(mixed $key, mixed $value): array
    {
        $label = 'Ver';

        // Legacy format: [['key' => 'folder.x', 'value' => '...']]
        if (is_array($value) && array_key_exists('key', $value) && array_key_exists('value', $value)) {
            $linkKey = is_scalar($value['key']) ? (string) $value['key'] : null;
            $linkValue = is_scalar($value['value']) ? (string) $value['value'] : '';

            if (isset($value['label']) && is_scalar($value['label'])) {
                $label = (string) $value['label'];
            }

            return [$linkKey, $linkValue, $label];
        }

        // Associative format: ['folder.x' => '...']
        if (is_string($key)) {
            $linkKey = $key;
            $linkValue = is_scalar($value) ? (string) $value : '';

            return [$linkKey, $linkValue, $label];
        }

        return [null, '', $label];
    }

    public static function normalizeDocumentSlug(string $slug): string
    {
        $slug = trim($slug);

        if ($slug === '') {
            return '';
        }

        return self::DOCUMENT_SLUG_ALIASES[$slug] ?? $slug;
    }

    public static function normalizeProcessSlug(string $slug): string
    {
        $slug = trim($slug);

        if ($slug === '') {
            return '';
        }

        return self::CHARACTERIZATION_SLUG_ALIASES[$slug] ?? $slug;
    }

    private function normalizeLinkLabel(string $label, string $fallback): string
    {
        $normalized = Str::lower(trim($label));

        if ($normalized === '' || $normalized === 'ver') {
            return $fallback;
        }

        return $label;
    }

    private function buildScheduleLink(
        string $slug,
        string $label,
        string $linkKey,
        string $linkValue,
        int $teamId
    ): ?array {
        $slug = trim($slug);

        if ($slug === '') {
            Log::warning('Missing slug for schedule.route link', [
                'key' => $linkKey,
                'team_id' => $teamId,
            ]);

            return null;
        }

        try {
            $schedule = Schedule::query()
                ->where('team_id', $teamId)
                ->where('slug', $slug)
                ->first();

            if ($schedule !== null) {
                $params = [
                    'tenant' => $teamId,
                    'record' => $schedule->getKey(),
                ];

                $resolvedLabel = $label;
                if (Str::lower(trim($label)) === 'ver cronograma') {
                    $resolvedLabel .= ' ' . ($schedule->name ?? $slug);
                }

                return [
                    'type' => 'route',
                    'url' => route('filament.admin.resources.cronogramas.edit', $params),
                    'label' => $resolvedLabel,
                    'route' => 'filament.admin.resources.cronogramas.edit',
                    'params' => $params,
                    'raw' => ['key' => $linkKey, 'value' => $linkValue],
                ];
            }

            $params = ['tenant' => $teamId];
            $url = route('filament.admin.resources.cronogramas.index', $params) .
                '?' .
                http_build_query(['tableSearch' => $slug]);

            return [
                'type' => 'route',
                'url' => $url,
                'label' => $label,
                'route' => 'filament.admin.resources.cronogramas.index',
                'params' => $params,
                'raw' => ['key' => $linkKey, 'value' => $linkValue],
            ];
        } catch (\Throwable $e) {
            Log::warning('Cannot generate schedule route', [
                'error' => $e->getMessage(),
                'key' => $linkKey,
                'value' => $linkValue,
                'slug' => $slug,
                'team_id' => $teamId,
            ]);
        }

        return null;
    }

    private function buildRouteLink(
        string $routeName,
        array $params,
        string $label,
        string $linkKey,
        string $linkValue,
        int $teamId
    ): ?array {
        try {
            $url = route($routeName, $params);

            return [
                'type' => 'route',
                'url' => $url,
                'label' => $label !== '' ? $label : 'Ver',
                'route' => $routeName,
                'params' => $params,
                'raw' => ['key' => $linkKey, 'value' => $linkValue],
            ];
        } catch (\Throwable $e) {
            Log::warning("Cannot generate route '{$routeName}'", [
                'error' => $e->getMessage(),
                'key' => $linkKey,
                'value' => $linkValue,
                'team_id' => $teamId,
                'params' => $params,
            ]);
        }

        return null;
    }

    private function inferSectionFromEntryId(string $entryId): string
    {
        if ($entryId === '') {
            return '';
        }

        return Str::before($entryId, '.');
    }

    /**
     * Kept for compatibility with previous calls.
     */
    protected function getFieldFromEntry(array $entry, string $field): ?string
    {
        if ($field === '') {
            return null;
        }

        $parts = explode('.', $field);
        $current = $entry;

        foreach ($parts as $part) {
            if (! is_array($current) || ! array_key_exists($part, $current)) {
                return null;
            }

            $current = $current[$part];
        }

        return is_scalar($current) ? (string) $current : null;
    }
}
