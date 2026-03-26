<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Setting;
use App\Models\TenantSetting;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StrategicPlatformFiles extends Page
{
    protected static string $view = 'filament.pages.tenancy.strategic-platform-files';
    protected static ?string $slug = 'plataforma-estrategica-archivos';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Plataforma Estratégica';
    protected static ?string $navigationLabel = 'Mapa y Organigrama';
    protected static ?string $title = 'Mapa de Procesos y Organigrama';

    public array $processMapFiles = [];
    public array $orgChartFiles = [];

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function mount(): void
    {
        $tenantId = Filament::getTenant()?->id;

        $filesByKey = $this->loadFiles([
            'Mapa de Procesos',
            'Organigrama',
        ], $tenantId);

        $this->processMapFiles = $filesByKey['Mapa de Procesos'] ?? [];
        $this->orgChartFiles = $filesByKey['Organigrama'] ?? [];
    }

    protected function loadFiles(array $keys, ?int $tenantId): array
    {
        $settings = Setting::whereIn('key', $keys)->get()->keyBy('key');

        $result = [];

        foreach ($keys as $key) {
            $setting = $settings->get($key);
            $value = $this->resolveSettingValue($setting, $tenantId);
            $disk = $this->diskForKey($key);
            $result[$key] = $this->normalizeFiles($value, $disk);
        }

        return $result;
    }

    protected function resolveSettingValue(?Setting $setting, ?int $tenantId)
    {
        if (! $setting) {
            return null;
        }

        $tenantValue = null;

        if ($tenantId) {
            $ts = TenantSetting::where('setting_id', $setting->id)
                ->where('team_id', $tenantId)
                ->latest('updated_at')
                ->first();

            if ($ts) {
                $tenantValue = $ts->value ?? Arr::get($ts->data ?? [], 'value');
                if (is_null($tenantValue) && is_array($ts->data) && ! empty($ts->data)) {
                    $tenantValue = $ts->data;
                }
            }
        }

        $final = $tenantValue ?? $setting->value;

        if (is_string($final)) {
            $decoded = json_decode($final, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $final = $decoded;
            }
        }

        return $final;
    }

    protected function normalizeFiles($value, string $disk): array
    {
        if (is_null($value) || $value === '') {
            return [];
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            } else {
                $value = [$value];
            }
        }

        if (! is_array($value)) {
            return [];
        }

        if (Arr::isAssoc($value) && isset($value['path'])) {
            $value = [$value];
        }

        $items = [];

        foreach ($value as $entry) {
            $item = $this->normalizeFileEntry($entry, $disk);
            if ($item) {
                $items[] = $item;
            }
        }

        return $items;
    }

    protected function normalizeFileEntry($entry, string $disk): ?array
    {
        if (is_string($entry)) {
            $entry = ['path' => $entry];
        }

        if (! is_array($entry)) {
            return null;
        }

        $path = $entry['path'] ?? $entry['file'] ?? $entry['url'] ?? null;

        if (! is_string($path) || trim($path) === '') {
            return null;
        }

        $description = $entry['description'] ?? $entry['descripcion'] ?? null;
        $meta = $entry['meta'] ?? $entry['metadata'] ?? $entry['metadatos'] ?? [];
        $metaLines = $this->buildMetaLines($meta);
        $url = $this->resolveFileUrl($path, $disk);
        $isDefault = $this->isDefaultPublicFile($path);
        $extension = strtolower((string) pathinfo($this->pathForExtension($path, $url), PATHINFO_EXTENSION));
        $isPdf = $extension === 'pdf';
        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'], true);

        return [
            'path' => $path,
            'url' => $url,
            'name' => basename($path),
            'description' => is_string($description) ? trim($description) : null,
            'meta_lines' => $metaLines,
            'extension' => $extension,
            'is_pdf' => $isPdf,
            'is_image' => $isImage,
            'is_default' => $isDefault,
        ];
    }

    protected function resolveFileUrl(string $path, string $disk): string
    {
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (Str::startsWith($path, ['/'])) {
            return $path;
        }

        if (file_exists(public_path($path))) {
            return '/' . ltrim($path, '/');
        }

        if (Str::startsWith($path, ['/storage/', 'storage/'])) {
            return '/' . ltrim($path, '/');
        }

        $normalized = $this->stripPublicPrefix($path);
        return Storage::disk($disk)->url($normalized);
    }

    protected function stripPublicPrefix(string $path): string
    {
        return Str::startsWith($path, 'public/')
            ? substr($path, 7)
            : $path;
    }

    protected function pathForExtension(string $path, string $url): string
    {
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return (string) (parse_url($path, PHP_URL_PATH) ?? $path);
        }

        if (Str::startsWith($path, ['/storage/', 'storage/'])) {
            return $path;
        }

        if ($url && Str::startsWith($url, ['http://', 'https://'])) {
            return (string) (parse_url($url, PHP_URL_PATH) ?? $path);
        }

        return $path;
    }

    protected function isDefaultPublicFile(string $path): bool
    {
        $defaults = [
            'public_process_map.png',
            'public_organization_chart.png',
        ];

        return in_array(basename($path), $defaults, true);
    }

    protected function diskForKey(string $key): string
    {
        return match ($key) {
            'Mapa de Procesos', 'Organigrama' => 'public',
            default => config('filament.default_filesystem_disk') ?? config('filesystems.default'),
        };
    }

    protected function buildMetaLines($meta): array
    {
        if (is_null($meta) || $meta === '') {
            return [];
        }

        if (is_string($meta) || is_numeric($meta) || is_bool($meta)) {
            return [$this->stringifyMetaValue($meta)];
        }

        if (! is_array($meta)) {
            return [];
        }

        $lines = [];

        foreach ($meta as $key => $value) {
            if (is_int($key)) {
                $lines[] = $this->stringifyMetaValue($value);
            } else {
                $label = trim((string) $key);
                $lines[] = $label . ': ' . $this->stringifyMetaValue($value);
            }
        }

        return $lines;
    }

    protected function stringifyMetaValue($value): string
    {
        if (is_array($value)) {
            $parts = [];
            foreach ($value as $item) {
                $parts[] = $this->stringifyMetaValue($item);
            }
            return implode(', ', $parts);
        }

        if (is_bool($value)) {
            return $value ? 'Sí' : 'No';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return json_encode($value);
    }
}
