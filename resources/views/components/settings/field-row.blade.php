@props([
    'label' => null,
    'value' => null,
    'type' => 'text',
    'help' => null,
    'key' => null,
    'attributesData' => [],
])

@php
    use App\Models\MinutesIvcSectionEntry as EntryType;
    use App\Services\LinkResolver;
    use Filament\Facades\Filament;
    use Illuminate\Support\Arr;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    // Flujo de la vista:
    // 1) Normaliza type/value/attributes.
    // 2) Normaliza links (estructura unica key/value/url/label).
    // 3) Prepara datos de salida segun entry_type (route/folder/upload).
    // 4) Renderiza el bloque principal y metadatos (criticidad/help).

    // Normaliza entry_type y mapea aliases legacy ("file" -> "upload").
    $rawType = is_string($type ?? null) ? strtolower(trim($type)) : EntryType::TEXT;
    $type = match ($rawType) {
        EntryType::UPLOAD, 'file' => EntryType::UPLOAD,
        EntryType::ROUTE => EntryType::ROUTE,
        EntryType::FOLDER => EntryType::FOLDER,
        default => EntryType::TEXT,
    };

    // Normaliza el valor de respuesta y metadatos opcionales.
    $value = is_scalar($value) ? (string) $value : null;
    $attributesData = is_array($attributesData)
        ? $attributesData
        : (is_string($attributesData)
            ? json_decode($attributesData, true)
            : []);

    // Contexto base de la fila (links, seccion inferida por entry_id y tenant actual).
    $linksInput = Arr::get($attributesData, 'links', []);
    $linksInput = is_array($linksInput) ? $linksInput : [];

    $rowEntryId = is_scalar($key) ? (string) $key : '';
    $rowSection = $rowEntryId !== '' ? Str::before($rowEntryId, '.') : '';
    $tenantFromRoute = request()->route('tenant');
    $tenantId = Filament::getTenant()?->id
        ?? (is_object($tenantFromRoute) && method_exists($tenantFromRoute, 'getKey')
            ? $tenantFromRoute->getKey()
            : $tenantFromRoute);

    // Unifica links en un formato canonico:
    // ['key' => string, 'value' => string, 'url' => ?string, 'label' => string]
    $normalizedLinks = [];
    foreach ($linksInput as $linkKey => $linkValue) {
        $rawKey = null;
        $rawValue = '';
        $resolvedUrl = null;
        $resolvedLabel = 'Ver';

        if (is_array($linkValue) && isset($linkValue['raw']) && is_array($linkValue['raw'])) {
            if (is_scalar($linkValue['raw']['key'] ?? null)) {
                $rawKey = (string) $linkValue['raw']['key'];
            }
            if (is_scalar($linkValue['raw']['value'] ?? null)) {
                $rawValue = (string) $linkValue['raw']['value'];
            }
            if (is_string($linkValue['url'] ?? null) && trim($linkValue['url']) !== '') {
                $resolvedUrl = (string) $linkValue['url'];
            }
            if (is_scalar($linkValue['label'] ?? null) && trim((string) $linkValue['label']) !== '') {
                $resolvedLabel = (string) $linkValue['label'];
            }
        } elseif (is_array($linkValue) && array_key_exists('key', $linkValue) && array_key_exists('value', $linkValue)) {
            if (is_scalar($linkValue['key'])) {
                $rawKey = (string) $linkValue['key'];
            }
            if (is_scalar($linkValue['value'])) {
                $rawValue = (string) $linkValue['value'];
            }
            if (is_scalar($linkValue['label'] ?? null) && trim((string) $linkValue['label']) !== '') {
                $resolvedLabel = (string) $linkValue['label'];
            }
        } elseif (is_string($linkKey)) {
            $rawKey = $linkKey;
            if (is_scalar($linkValue)) {
                $rawValue = (string) $linkValue;
            }
        }

        if ($rawKey === null || $rawKey === '') {
            continue;
        }

        $normalizedLinks[] = [
            'key' => $rawKey,
            'value' => $rawValue,
            'url' => $resolvedUrl,
            'label' => $resolvedLabel,
        ];
    }

    // Para ROUTE: arma enlaces clickeables segun key:
    // - document.slug
    // - page.route
    // - record.route
    // - schedule.route
    // Tambien soporta legacy document.<slug>.
    $routeLinks = [];
    if ($type === EntryType::ROUTE) {
        foreach ($normalizedLinks as $link) {
            $routeKey = $link['key'];
            $routeValue = trim($link['value']);
            $routeUrl = is_string($link['url']) && $link['url'] !== '' ? $link['url'] : null;
            $routeLabel = is_string($link['label']) && $link['label'] !== '' ? $link['label'] : 'Ver';
            $routeTargetName = '';
            $routeTooltipName = '';

            $normalizedRouteLabel = Str::lower(trim($routeLabel));
            $isGenericRouteLabel = in_array($normalizedRouteLabel, ['ver', ''], true);

            if ($tenantId) {
                try {
                    if ($routeKey === 'document.slug' && $routeValue !== '') {
                        $slug = LinkResolver::normalizeDocumentSlug($routeValue);
                        // Forzar siempre tenant + document para evitar enlaces incompletos.
                        $routeUrl = route('document.details', [
                            'tenant' => $tenantId,
                            'document' => $slug,
                        ]);
                        $routeTargetName = $slug;
                    } elseif ($routeKey === 'page.route' && $routeValue !== '') {
                        if ($routeUrl === null) {
                            $routeUrl = route($routeValue, ['tenant' => $tenantId]);
                        }
                    } elseif ($routeKey === 'record.route' && $routeValue !== '') {
                        if ($routeUrl === null) {
                            $routeUrl = route($routeValue, ['tenant' => $tenantId]);
                        }
                    } elseif ($routeKey === 'schedule.route') {
                        if ($routeUrl === null) {
                            $routeUrl = route('filament.admin.resources.quality.schedules.index', ['tenant' => $tenantId]);
                            if ($routeValue !== '') {
                                $routeUrl .= '?' . http_build_query(['tableSearch' => $routeValue]);
                            }
                        }
                    } elseif (Str::startsWith($routeKey, 'document.') && $routeKey !== 'document.slug') {
                        $slug = LinkResolver::normalizeDocumentSlug(Str::after($routeKey, 'document.'));
                        $routeName = $routeValue !== '' ? $routeValue : 'document.details';
                        $routeTargetName = $slug;
                        if ($slug !== '') {
                            if ($routeUrl === null) {
                                $routeUrl = route($routeName, [
                                    'tenant' => $tenantId,
                                    'document' => $slug,
                                ]);
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    $routeUrl = null;
                }
            }

            if ($isGenericRouteLabel) {
                $routeBaseLabel = 'Ver';
                if ($routeKey === 'document.slug' || (Str::startsWith($routeKey, 'document.') && $routeKey !== 'document.slug')) {
                    $routeBaseLabel = 'Ver documento';
                    if ($routeTargetName === '' && $routeValue !== '') {
                        $routeTargetName = $routeValue;
                    }
                } elseif ($routeKey === 'schedule.route') {
                    $routeBaseLabel = 'Ver cronograma';
                    $routeTargetName = $routeValue;
                } elseif ($routeKey === 'record.route') {
                    $routeBaseLabel = 'Ver registro';
                    $routeTargetName = $routeValue;
                } elseif ($routeKey === 'page.route') {
                    $routeBaseLabel = 'Ver pagina';
                    $routeTargetName = $routeValue;
                }

                $routeLabel = $routeTargetName !== ''
                    ? $routeBaseLabel . ' ' . $routeTargetName
                    : $routeBaseLabel;
            }

            if ($routeTargetName !== '') {
                $routeTooltipName = $routeTargetName;
            } elseif ($routeValue !== '' && ! Str::contains($routeValue, '.')) {
                $routeTooltipName = $routeValue;
            }

            if ($routeTooltipName === '' && is_string($routeUrl) && $routeUrl !== '') {
                $routeTooltipName = urldecode(pathinfo(parse_url($routeUrl, PHP_URL_PATH) ?? '', PATHINFO_BASENAME));
            }

            if ($routeUrl !== null && $routeUrl !== '') {
                $routeLinks[] = [
                    'url' => $routeUrl,
                    'label' => $routeLabel,
                    'tooltip' => $routeTooltipName,
                ];
            }
        }
    }

    // Para FOLDER: arma el texto fijo con seccion + key + value.
    $folderMessages = [];
    if ($type === EntryType::FOLDER) {
        foreach ($normalizedLinks as $link) {
            $folderKey = $link['key'];
            $folderValue = trim($link['value']);
            $section = $rowSection !== '' ? $rowSection : 'N/D';
            $fieldKey = $folderKey;

            if (Str::startsWith($folderKey, 'folder.')) {
                $section = Str::after($folderKey, 'folder.');
                $fieldKey = Str::before($folderKey, '.');
            } elseif (Str::contains($folderKey, '.')) {
                $section = Str::before($folderKey, '.');
                $fieldKey = Str::after($folderKey, '.');
            }

            $folderMessages[] = 'Este documento se encuentra en la carpeta fisica, en la seccion '
                . $section
                . ', bajo la '
                . $fieldKey
                . ' numero '
                . ($folderValue !== '' ? $folderValue : 'N/D');
        }
    }

    // Para UPLOAD: resuelve URL(s) del archivo usando answer como path
    // y metadata opcional de links (disk/path/url).
    $uploadLinks = [];
    if ($type === EntryType::UPLOAD) {
        $answerPath = $value !== null ? trim($value) : '';

        if ($answerPath !== '') {
            if (filter_var($answerPath, FILTER_VALIDATE_URL)) {
                $uploadLinks[] = $answerPath;
            } elseif (Str::startsWith($answerPath, '/')) {
                $uploadLinks[] = $answerPath;
            } elseif (! empty($normalizedLinks)) {
                foreach ($normalizedLinks as $link) {
                    $uploadUrl = null;
                    $uploadKey = strtolower(trim($link['key']));
                    $uploadValue = trim($link['value']);

                    try {
                        if (($uploadKey === 'path' || $uploadKey === 'disk') && $uploadValue !== '') {
                            $uploadUrl = Storage::disk($uploadValue)->url($answerPath);
                        } elseif ($uploadKey === 'url' && $uploadValue !== '') {
                            $uploadUrl = rtrim($uploadValue, '/') . '/' . ltrim($answerPath, '/');
                        } else {
                            $uploadUrl = Storage::url($answerPath);
                        }
                    } catch (\Throwable $e) {
                        $uploadUrl = null;
                    }

                    if ($uploadUrl !== null && ! in_array($uploadUrl, $uploadLinks, true)) {
                        $uploadLinks[] = $uploadUrl;
                    }
                }
            } else {
                $uploadLinks[] = Storage::url($answerPath);
            }
        }
    }

    // Color del badge de criticidad.
    $criticalityColors = [
        'critico' => 'danger',
        'Critico' => 'danger',
        'CrÃ­tico' => 'danger',
        'Mayor' => 'warning',
        'menor' => 'info',
    ];
    $criticality = $attributesData['criticality'] ?? null;
    $color = $criticalityColors[$criticality] ?? 'secondary';
@endphp

<div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-b last:border-b-0">
    <dt class="text-sm font-medium text-gray-600 flex flex-wrap items-start gap-x-3 gap-y-2">
        {{-- Encabezado de la fila: entry_id + pregunta + criticidad --}}
        <p class="min-w-0 flex-1 break-words">{{ ($key ? $key . ' - ' : '') . $label }}</p>
        @if (!empty($criticality))
            <x-filament::badge size="xs" color="{{ $color }}" class="shrink-0 whitespace-nowrap">
                {{ $criticality }}
            </x-filament::badge>
        @endif
    </dt>

    <dd class="mt-1 ml-3 text-sm text-gray-900 sm:col-span-2 sm:mt-0 flex flex-col gap-3">
        {{-- Render principal segun entry_type estandarizado --}}
        @if ($type === EntryType::TEXT)
            <span class="text-sm">{{ $value !== '' && $value !== null ? $value : '-' }}</span>
        @elseif ($type === EntryType::FOLDER)
            @if (! empty($folderMessages))
                <div class="space-y-1 text-sm">
                    @foreach ($folderMessages as $folderMessage)
                        <p class="text-sm">{{ $folderMessage }}</p>
                    @endforeach
                </div>
            @else
                <span class="text-gray-500">Sin referencia de carpeta</span>
            @endif
        @elseif ($type === EntryType::UPLOAD)
            @if (! empty($uploadLinks))
                <div class="space-y-1 text-sm">
                    @foreach ($uploadLinks as $uploadUrl)
                        @php
                            $uploadName = urldecode(pathinfo(parse_url($uploadUrl, PHP_URL_PATH) ?? '', PATHINFO_BASENAME));
                        @endphp
                        <a href="{{ $uploadUrl }}" target="_blank" class="underline block" @if ($uploadName !== '') title="{{ $uploadName }}" @endif>
                            Ver documento{{ $uploadName !== '' ? ' ' . $uploadName : '' }}
                        </a>
                    @endforeach
                </div>
            @else
                <span class="text-gray-500">Sin archivo</span>
            @endif
        @elseif ($type === EntryType::ROUTE)
            @if (! empty($routeLinks))
                <div class="space-y-1 text-sm">
                    @foreach ($routeLinks as $link)
                        <a href="{{ $link['url'] }}" target="_blank" class="underline block" @if (($link['tooltip'] ?? '') !== '') title="{{ $link['tooltip'] }}" @endif>
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            @else
                <span class="text-gray-500">Link no disponible</span>
            @endif
        @else
            <span class="text-sm">{{ $value !== '' && $value !== null ? $value : '-' }}</span>
        @endif

        @php
            $complianceRaw = $attributesData['compliance'] ?? null;
            $hasCompliance = $complianceRaw !== null && $complianceRaw !== '';
            $compliance = filter_var($complianceRaw, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        @endphp
        @if ($hasCompliance)
            <span class="text-xs">
                Cumplimiento:
                @if ($compliance === true)
                    <x-filament::badge color="success">Cumple</x-filament::badge>
                @else
                    <x-filament::badge color="danger">No cumple</x-filament::badge>
                @endif
            </span>
        @endif

        @if (!empty($attributesData['help'] ?? $help))
            <p class="text-xs text-gray-500 mt-1">{{ $attributesData['help'] ?? $help }}</p>
        @endif
    </dd>
</div>
