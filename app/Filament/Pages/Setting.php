<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\Settings;
use App\Models\Setting as ModelsSetting;
use App\Models\TenantSetting;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Setting extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'Configuración de plataforma';
    //protected static ?string $navigationIcon = 'phosphor-faders';
    protected static ?string $navigationLabel = 'Plataforma Estratégica';
    protected static ?string $title = 'Plataforma Estratégica';
    protected static ?string $slug = 'plataforma-estrategica';

    public ?array $data = [];

    protected static string $view = 'filament.pages.setting';
    public static function canAccess(): bool
    {
        return true;
    }

    public function mount(): void
    {
        // Get the current tenant
        $tenant = Filament::getTenant();

        // first lets get all the settings key from admin panel.
        $settings = ModelsSetting::all()
            ->groupBy('group')
            ->map(function ($items) {
                return $items->pluck('value', 'key');
            })
            ->toArray();

        // fetch all tenant specific setttings.
        $tenantSettings = TenantSetting::where('team_id', $tenant->id)->get()->keyBy('setting_id');

        $mergedSettings = [];
        // merge them.
        foreach ($settings as $group => $items) {
            foreach ($items as $key => $value) {
                $setting = ModelsSetting::where('key', $key)->first();

                $tenantSetting = $tenantSettings->get($setting->id);

                if ($tenantSetting) {
                    $finalValue = $tenantSetting->value;
                } else {
                    $finalValue = $value;
                }

                if ($setting?->type === 'file') {
                    $finalValue = $this->normalizeFileSettingForForm($finalValue);
                }

                $mergedSettings[$group][$key] = $finalValue;
            }
        }
        // this will fill our form with our merged data.
        $this->form->fill($mergedSettings);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs($this->generateTabs())
            ])
            ->statePath('data');
    }

    protected function generateTabs(): array
    {
        $settings = ModelsSetting::all()->groupBy('group');

        return $settings->map(function (Collection $moduleSettings, string $module) {
            return Tab::make($module)
                ->label(str($module)->title()->replace('_', ' '))
                ->schema(
                    $moduleSettings->map(function ($setting) {
                        return $this->generateField($setting);
                    })->toArray()
                );
        })->toArray();
    }

    public function generateField($setting)
    {
        $label = str($setting->key)->title()->replace("_", " ");
        $key_cut = \Illuminate\Support\Str::words($setting->key, 4);
        $name = "{$setting->group}.{$key_cut}";
        return match ($setting->type) {
            'text' => TextInput::make($name)
                ->label($label),

            'boolean' => Toggle::make($name)
                ->label($label),

            'select' => Select::make($name)
                ->label($label)
                ->options(function () use ($setting) {
                    return $setting->attributes['options'];
                }),

            'file' => $this->buildFileRepeaterField($name, $label, $setting),

            'repeater' => Repeater::make($name)
                ->label($label)
                ->schema([
                    TextInput::make(''),
                ])
                ->columns(1),

            'textarea' => Textarea::make($name)
                ->label($label)
                ->schema([
                    TextInput::make(''),
                ])
                ->columns(1),


            'key-value' => KeyValue::make($name)
                ->label($label)
                ->columnSpanFull(),

            default => TextInput::make($name)
                ->label($label)
        };
    }

    public function save(): void
    {
        $tenantId = Filament::getTenant()->id;
        foreach ($this->form->getState() as $group) {
            // dd($group);
            if (is_array($group)) {
                foreach ($group as $key => $value) {
                    // dd($key);
                    $settings[$key] = $value;
                    //dd($settings);
                }
            }
        }
        foreach ($settings as $key => $value) {
            $setting = ModelsSetting::where('key', $key)->first();
            if ($setting) {
                $existingTenantSetting = null;
                $oldPaths = [];

                if ($setting->type === 'file') {
                    $existingTenantSetting = TenantSetting::where('setting_id', $setting->id)
                        ->where('team_id', $tenantId)
                        ->latest('updated_at')
                        ->first();

                    if ($existingTenantSetting) {
                        $oldPaths = $this->extractPathsFromStoredValue($existingTenantSetting->value);
                    }

                    $value = $this->normalizeFileSettingForSave($value);
                }

                TenantSetting::updateOrCreate([
                    "setting_id" => $setting->id,
                    "team_id" => $tenantId
                ], [
                    "value" => $value
                ]);

                if ($setting->type === 'file' && $existingTenantSetting) {
                    $newPaths = $this->extractPathsFromStoredValue($value);
                    $removedPaths = array_diff($oldPaths, $newPaths);
                    $this->deleteRemovedFiles($removedPaths, $this->diskForSetting($setting));
                }
            }
        }

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }

    protected function normalizeFileSettingForForm($value): array
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
            $item = $this->normalizeFileEntryForForm($entry);
            if ($item) {
                $items[] = $item;
            }
        }

        return $items;
    }

    protected function normalizeFileEntryForForm($entry): ?array
    {
        if (is_string($entry)) {
            return ['path' => $entry, 'description' => null, 'meta' => []];
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

        if (! is_array($meta)) {
            $meta = ['valor' => (string) $meta];
        }

        return [
            'path' => $path,
            'description' => is_string($description) ? trim($description) : null,
            'meta' => $meta,
        ];
    }

    protected function normalizeFileSettingForSave($value): array
    {
        if (is_null($value) || $value === '') {
            return [];
        }

        if (is_string($value)) {
            return [['path' => $value]];
        }

        if (! is_array($value)) {
            return [];
        }

        $items = [];

        foreach ($value as $entry) {
            if (! is_array($entry)) {
                continue;
            }

            $path = $entry['path'] ?? null;
            if (! is_string($path) || trim($path) === '') {
                continue;
            }

            $item = ['path' => $path];

            $description = $entry['description'] ?? null;
            if (is_string($description) && trim($description) !== '') {
                $item['description'] = trim($description);
            }

            $meta = $entry['meta'] ?? null;
            if (is_array($meta) && ! empty($meta)) {
                $item['meta'] = $meta;
            }

            $items[] = $item;
        }

        return $items;
    }

    protected function extractPathsFromStoredValue($value): array
    {
        if (is_null($value) || $value === '') {
            return [];
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            } else {
                return [trim($value)];
            }
        }

        if (! is_array($value)) {
            return [];
        }

        if (Arr::isAssoc($value) && isset($value['path'])) {
            $value = [$value];
        }

        $paths = [];

        foreach ($value as $entry) {
            if (is_string($entry)) {
                $path = trim($entry);
            } elseif (is_array($entry)) {
                $path = $entry['path'] ?? $entry['file'] ?? $entry['url'] ?? null;
                $path = is_string($path) ? trim($path) : null;
            } else {
                $path = null;
            }

            if ($path) {
                $paths[] = $path;
            }
        }

        return array_values(array_unique($paths));
    }

    protected function deleteRemovedFiles(array $paths, string $disk): void
    {
        $protected = $this->protectedDefaultPublicFiles();

        foreach ($paths as $path) {
            if (! is_string($path) || trim($path) === '') {
                continue;
            }

            if (in_array(basename($path), $protected, true)) {
                continue;
            }

            if (
                str_starts_with($path, 'http://') ||
                str_starts_with($path, 'https://')
            ) {
                continue;
            }

            if (Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);
            }
        }
    }

    protected function buildFileRepeaterField(string $name, string $label, $setting): Repeater
    {
        $repeater = Repeater::make($name)
            ->label($label)
            ->schema([
                $this->buildFileUploadField($setting),
                Textarea::make('description')
                    ->label('Descripción')
                    ->rows(3),
                KeyValue::make('meta')
                    ->label('Metadatos')
                    ->keyLabel('Clave')
                    ->valueLabel('Valor')
                    ->columnSpanFull(),
            ])
            ->columns(1);

        $default = $this->defaultFilesForSetting($setting);
        if (! is_null($default)) {
            $repeater->hintAction(
                Action::make('restoreDefault')
                    ->label('Restaurar predeterminado')
                    ->action(function (Repeater $component) use ($default) {
                        if (! is_array($default)) {
                            return;
                        }

                        $items = [];

                        foreach ($default as $itemData) {
                            $uuid = $component->generateUuid();
                            if ($uuid) {
                                $items[$uuid] = $itemData;
                            } else {
                                $items[] = $itemData;
                            }
                        }

                        $component->state($items);

                        foreach ($items as $uuid => $itemData) {
                            $container = $component->getChildComponentContainer($uuid);
                            if ($container) {
                                $container->fill($itemData);
                            }
                        }

                        $component->callAfterStateUpdated();
                    })
            );
        }

        return $repeater;
    }

    protected function buildFileUploadField($setting): FileUpload
    {
        $upload = FileUpload::make('path')
            ->label('Archivo')
            ->multiple(false)
            ->previewable()
            ->openable()
            ->downloadable()
            ->deletable()
            ->getUploadedFileUsing(function ($component, string $file, $storedFileNames) use ($setting) {
                return $this->buildUploadedFilePayload($file, $storedFileNames, $setting);
            });

        $config = $this->fileConfigForSetting($setting);

        if (! empty($config['disk'])) {
            $upload->disk($config['disk']);
        }

        if (! empty($config['directory'])) {
            $upload->directory($config['directory']);
        }

        if (! empty($config['accepted_types'])) {
            $upload->acceptedFileTypes($config['accepted_types']);
        }

        if (! empty($config['max_size_kb'])) {
            $upload->maxSize($config['max_size_kb']);
        }

        return $upload;
    }

    protected function diskForSetting($setting): string
    {
        $config = $this->fileConfigForSetting($setting);
        return ! empty($config['disk'])
            ? $config['disk']
            : (config('filament.default_filesystem_disk') ?? config('filesystems.default'));
    }

    protected function resolveFileUrlForSetting($file, $setting): ?string
    {
        if (! is_string($file) || trim($file) === '') {
            return null;
        }

        if (Str::startsWith($file, ['http://', 'https://'])) {
            return $file;
        }

        if (Str::startsWith($file, ['/'])) {
            return $file;
        }

        if (file_exists(public_path($file))) {
            return '/' . ltrim($file, '/');
        }

        if (Str::startsWith($file, ['storage/', '/storage/'])) {
            return '/' . ltrim($file, '/');
        }

        $path = Str::startsWith($file, 'public/') ? substr($file, 7) : $file;
        return Storage::disk($this->diskForSetting($setting))->url($path);
    }

    protected function buildUploadedFilePayload(string $file, $storedFileNames, $setting): ?array
    {
        $disk = $this->diskForSetting($setting);
        $normalized = Str::startsWith($file, 'public/') ? substr($file, 7) : $file;
        $publicCandidate = ltrim($file, '/');

        $url = $this->resolveFileUrlForSetting($file, $setting);
        if (! $url) {
            return null;
        }

        $name = is_array($storedFileNames)
            ? ($storedFileNames[$file] ?? basename($file))
            : ($storedFileNames ?? basename($file));

        $size = 0;
        $type = null;

        if (Storage::disk($disk)->exists($normalized)) {
            $size = Storage::disk($disk)->size($normalized);
            $type = Storage::disk($disk)->mimeType($normalized);
        } elseif (file_exists(public_path($publicCandidate))) {
            $path = public_path($publicCandidate);
            $size = @filesize($path) ?: 0;
            $type = @mime_content_type($path) ?: null;
        }

        return [
            'name' => $name,
            'size' => $size,
            'type' => $type,
            'url' => $url,
        ];
    }

    protected function protectedDefaultPublicFiles(): array
    {
        return [
            'public_process_map.png',
            'public_organization_chart.png',
        ];
    }

    protected function defaultFilesForSetting($setting): ?array
    {
        if (! $setting) {
            return null;
        }

        return match ($setting->key) {
            'Mapa de Procesos' => [['path' => 'public_process_map.png']],
            'Organigrama' => [['path' => 'public_organization_chart.png']],
            default => null,
        };
    }

    protected function fileConfigForSetting($setting): array
    {
        if (! $setting) {
            return [];
        }

        $tenantId = Filament::getTenant()?->id;
        $tenantFolder = $tenantId ? 'tenants/' . $tenantId : 'tenants';

        return match ($setting->key) {
            'Logo' => [
                'disk' => 'public',
                'directory' => $tenantFolder . '/plataforma-estrategica/logo',
                'accepted_types' => [
                    'image/jpeg',
                    'image/png',
                    'image/webp',
                    'image/svg+xml',
                ],
                'max_size_kb' => 2048,
            ],
            'Mapa de Procesos' => [
                'disk' => 'public',
                'directory' => $tenantFolder . '/plataforma-estrategica/mapa-de-procesos',
                'accepted_types' => [
                    'application/pdf',
                    'image/jpeg',
                    'image/png',
                    'image/webp',
                ],
                'max_size_kb' => 15360,
            ],
            'Organigrama' => [
                'disk' => 'public',
                'directory' => $tenantFolder . '/plataforma-estrategica/organigrama',
                'accepted_types' => [
                    'application/pdf',
                    'image/jpeg',
                    'image/png',
                    'image/webp',
                ],
                'max_size_kb' => 10240,
            ],
            default => [],
        };
    }
}
