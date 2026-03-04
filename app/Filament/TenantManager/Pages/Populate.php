<?php


namespace App\Filament\TenantManager\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

use App\Models\Team;
use App\Models\ManagementIndicator;
use App\Models\MinutesIvcSection;
use App\Models\MinutesIvcSectionEntry;
use App\Models\Document;
use App\Models\Process;
use App\Models\ProcessType;
use App\Models\DocumentCategory;
use App\Models\Schedule;
use App\Models\Event;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class Populate extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'phosphor-database';
    protected static string $view = 'filament.tenant-manager.pages.populate';
    protected static ?string $title = 'Poblar Team desde config';

    // Datos del formulario
    public ?array $formData = [
        'team_id'    => null,
        'config_key' => null,
    ];

    public function mount(): void
    {
        //$this->form->fill($this->formData);
        $this->form->fill();
    }

    /**
     * Opciones de archivos de configuración
     * Ajusta y/o extiende según tus config reales.
     */
    protected function getConfigOptions(): array
    {
        return [
            'management-indicators'                 => 'Indicadores de gestión',
            'minutes-ivc-sections'                  => 'Secciones IVC (estructura)',
            'minutes-ivc-second-section-entries'    => 'Entradas IVC - Sección 2',
            'minutes-ivc-third-section-entries'    => 'Entradas IVC - Sección 3',
            'minutes-ivc-fourth-section-entries'    => 'Entradas IVC - Sección 4',
            'minutes-ivc-fifth-section-entries'    => 'Entradas IVC - Sección 5',
            'minutes-ivc-sixth-section-entries'    => 'Entradas IVC - Sección 6',
            'minutes-ivc-seventh-section-entries'    => 'Entradas IVC - Sección 7',
            'minutes-ivc-eighth-section-entries'    => 'Entradas IVC - Sección 8',
            'minutes-ivc-nine-section-entries'    => 'Entradas IVC - Sección 9',
            'document_templates.default_docs'       => 'Plantillas de documentos',
            'training_schedule'                     => 'Cronograma de capacitación',
            // agrega más según tus configs
        ];
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('team_id')
                    ->label('Seleccione Team')
                    ->options(Team::query()->orderBy('name')->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->required(),
                Select::make('config_key')
                    ->label('Archivo de configuración a aplicar')
                    ->options($this->getConfigOptions())
                    ->required(),
            ])
            ->statePath('formData');
    }

    /**
     * Acción pública que dispara el poblamiento (custom action).
     * Vincula el botón desde la vista Blade con wire:click="populateSelected"
     */
    public function populateSelected()
    {
        // Validar inputs del formulario Filament
        //$this->form->fill($this->formData);
        $data = $this->form->getState();
        $teamId = (int) ($data['team_id'] ?? 0);
        $configKey = $data['config_key'] ?? null;

        if (! $teamId || ! $configKey) {
            Notification::make()
                ->title('Formulario incompleto')
                ->danger()
                ->body('Debes seleccionar un team y un archivo de configuración.')
                ->send();
            return;
        }

        $team = Team::find($teamId);
        if (! $team) {
            Notification::make()
                ->title('Team no encontrado')
                ->danger()
                ->send();
            return;
        }

        try {
            DB::transaction(function () use ($team, $configKey) {
                $this->handlePopulate($team, $configKey);
            });

            // Notificación visual
            Notification::make()
                ->title('Población completada')
                ->success()
                ->body("Se aplicó correctamente '{$this->getConfigOptions()[$configKey]}' al team '{$team->name}'.")
                ->send();

            $schedule = Schedule::create([
                'team_id' => $team->id,
                'user_id' => Auth::id(),
                'name' => "Población desde config: {$configKey}",
                'description' => "El archivo de configuración '{$configKey}' fue aplicado al team name={$team->name}). Pasar a checkear!",
            ]);
            // Registro en DB: guardamos un Event para ese team como "notificación interna"
            Event::create([
                'team_id'    => $team->id,
                'user_id'    => Auth::id(),
                'role_id'    => null,
                'schedule_id' => $schedule->id,
                'title'      => "Población desde config: {$configKey}",
                'description' => "El archivo de configuración '{$configKey}' fue aplicado al team name={$team->name})",
                'type'       => 'task',
                'start_date' => now(),
                'end_date'   => now()->addMonth(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Error al poblar team desde config', [
                'team_id' => $team->id,
                'config'  => $configKey,
                'error'   => $e->getMessage(),
            ]);

            Notification::make()
                ->title('Error durante el poblamiento')
                ->danger()
                ->body("Ocurrió un error: " . $e->getMessage())
                ->send();

            // Volvemos a lanzar la excepción si quieres que el devtools la capture
            throw $e;
        }
    }

    /**
     * Handler central que delega en funciones por configKey
     */
    protected function handlePopulate(Team $team, string $configKey): void
    {
        $consultant = $this->getConsultant();

        switch ($configKey) {
            case 'management-indicators':
                $this->populateManagementIndicators($team);
                break;

            case 'minutes-ivc-sections':
                $this->populateIvcSections($team);
                break;

            case 'minutes-ivc-first-section-entries':
            case 'minutes-ivc-second-section-entries':
            case 'minutes-ivc-third-section-entries':
            case 'minutes-ivc-fourth-section-entries':
            case 'minutes-ivc-fifth-section-entries':
            case 'minutes-ivc-sixth-section-entries':
            case 'minutes-ivc-seventh-section-entries':
            case 'minutes-ivc-eighth-section-entries':
            case 'minutes-ivc-nine-section-entries':
                // Para entradas de secciones usamos el mismo método que recibe el configKey
                $this->populateIvcSectionEntries($team, $configKey);
                break;

            case 'document_templates.default_docs':
                $this->populateDocumentTemplates($team, $consultant);
                break;

            case 'training_schedule':
                $this->populateTrainingSchedule($team);
                break;

            default:
                throw new \InvalidArgumentException("Clave de configuración no reconocida: {$configKey}");
        }
    }

    private function getConsultant(): ?User
    {
        $consultantId = config('app.default_consultant_id', 1);
        return User::find($consultantId);
    }

    /**
     * Implementaciones de poblamiento (ejemplos idempotentes).
     * Ajusta los campos según tus modelos reales.
     */

    protected function populateManagementIndicators(Team $team): void
    {
        $names = config('management-indicators', []);
        if (! is_array($names) || empty($names)) return;

        $indicators = ManagementIndicator::whereIn('name', $names)->get()->keyBy('name');

        // recuperar o crear rol administrador del team si existe la relación por nombre+team_id
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate([
            'name' => 'Administrador',
            'guard_name' => 'web',
            'team_id' => $team->id,
        ]);

        foreach ($names as $name) {
            $indicator = $indicators->get($name);
            if (! $indicator) {
                Log::warning("management-indicator no encontrado: {$name}");
                continue;
            }
            $team->managementIndicators()->syncWithoutDetaching([
                $indicator->id => [
                    'role_id' => $adminRole->id,
                    'periodicity' => 'Mensual',
                    'indicator_goal' => $indicator->indicator_goal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    protected function populateIvcSections(Team $team): void
    {
        $sections = config('minutes-ivc-sections', []);
        if (! is_array($sections)) return;

        foreach ($sections as $s) {
            Log::alert('Creando sección IVC', ['section' => $s]);

            MinutesIvcSection::updateOrCreate(
                [
                    'team_id' => $team->id,
                    'slug'    => $s['slug'] ?? null,
                ],
                [
                    'order'       => $s['order'] ?? null,
                    'route'       => $s['route'] ?? null,
                    'name'        => $s['name'] ?? null,
                    'description' => $s['description'] ?? null,
                    'status'      => $s['status'] ?? null,
                ]
            );
        }
    }

    protected function populateIvcSectionEntries(Team $team, string $configKey): void
    {
        // El configKey debe corresponder a la clave en config y devolver un array de entradas
        $rawEntries = config($configKey, []);
        if (! is_array($rawEntries) || empty($rawEntries)) return;
        //dd($this->flattenMinutesIvcEntries($rawEntries));
        
        $entries = $this->flattenMinutesIvcEntries($rawEntries);
        if (empty($entries)) return;

        // Mapeo de prefijos de entry_id a nombre de sección
        $sectionPrefixes = [
            '2.'  => 'Talento Humano',
            '3.'  => 'Infraestructura Física',
            '4.'  => 'Saneamiento de edificaciones',
            '5.'  => 'Áreas',
            '6.'  => 'Sistema de gestión de calidad',
            '7.'  => 'Procesos y procedimientos',
            '8.'  => 'Revisión de productos',
            '9.'  => 'Revisión de otros aspectos',
        ];

        foreach ($entries as $e) {
            if (empty($e['entry_id'])) {
                Log::warning('Entrada IVC sin entry_id', ['config' => $configKey, 'entry' => $e]);
                continue;
            }

            $entryId = $e['entry_id'] ?? '';
            $sectionName = null;

            // Determinar el nombre de la sección según el prefijo de entry_id
            foreach ($sectionPrefixes as $prefix => $name) {
                if (str_starts_with($entryId, $prefix)) {
                    $sectionName = $name;
                    break;
                }
            }
            $sectionId = null;
            if ($sectionName) {
                $section = MinutesIvcSection::where('team_id', $team->id)
                    ->where('name', $sectionName)
                    ->first();
                $sectionId = $section?->id;
            } elseif (!empty($e['minutes_ivc_section_id'])) {
                $sectionId = $e['minutes_ivc_section_id'];
            }

            if (! $sectionId) {
                Log::warning('No se pudo determinar minutes_ivc_section_id para entrada', ['entry' => $e]);
                continue;
            }

            MinutesIvcSectionEntry::updateOrCreate(
                [
                    'minutes_ivc_section_id' => $sectionId,
                    'entry_id' => $e['entry_id'],
                ],
                [
                    'question' => $e['question'] ?? null,
                    'apply' => $e['apply'] ?? true,
                    'criticality' => $e['criticality'] ?? null,
                    'answer' => $e['answer'] ?? null,
                    'entry_type' => MinutesIvcSectionEntry::normalizeEntryType($e['entry_type'] ?? null),
                    'links' => $e['links'] ?? null,
                    'compliance' => $e['compliance'] ?? null,
                ]
            );
        }
    }

    private function flattenMinutesIvcEntries(array $node): array
    {
        $flat = [];

        foreach ($node as $item) {
            if (!is_array($item)) {
                continue;
            }

            if ($this->isMinutesIvcLeafEntry($item)) {
                $flat[] = $item;
                continue;
            }

            $flat = array_merge($flat, $this->flattenMinutesIvcEntries($item));
        }

        return $flat;
    }

    private function isMinutesIvcLeafEntry(array $item): bool
    {
        return array_key_exists('entry_id', $item) && array_key_exists('question', $item);
    }

    protected function populateDocumentTemplates(Team $team, ?User $consultant): void
    {
        $templates = $this->loadDocumentTemplates();
        if (! is_array($templates) || empty($templates)) {
            throw new \RuntimeException('No hay plantillas en config(document_templates.default_docs).');
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $templateSlugs = [];
        $skipReasons = [];

        foreach ($templates as $tpl) {
            $slug = is_string($tpl['slug'] ?? null) ? trim($tpl['slug']) : '';
            if ($slug === '') {
                Log::warning('Plantilla sin slug, se omite.', ['tpl' => $tpl]);
                $skipped++;
                $skipReasons[] = 'slug vacio';
                continue;
            }

            $templateSlugs[] = $slug;

            $processId = $this->resolveTemplateProcessId($team, $tpl);
            $categoryId = $this->resolveTemplateCategoryId($team, $tpl);

            if (! $processId || ! $categoryId) {
                Log::warning('Plantilla omitida: proceso o category no encontrados', [
                    'slug' => $slug,
                    'process_code' => $tpl['process_id'] ?? null,
                    'category_code' => $tpl['document_category_id'] ?? null,
                ]);
                $skipped++;
                $skipReasons[] = "{$slug}: process/category no resuelto";
                continue;
            }

            // Incluimos eliminados para restaurarlos si ya existian.
            $document = Document::withTrashed()->firstOrNew(
                [
                    'team_id' => $team->id,
                    'slug' => $slug,
                ]
            );

            if ($document->exists && method_exists($document, 'trashed') && $document->trashed()) {
                $document->restore();
            }

            // Si el documento ya existe y ha sido actualizado al menos una vez, continuamos.
            if (
                $document->exists
                && $document->updated_at
                && $document->created_at
                && $document->updated_at->gt($document->created_at)
            ) {
                $skipped++;
                continue;
            }

            $wasExisting = $document->exists;

            // Si es nuevo o nunca ha sido actualizado, lo llenamos con los datos y guardamos.
            $document->fill([
                'title' => $tpl['title'] ?? null,
                'sequence' => $tpl['sequence'] ?? 0,
                'process_id' => $processId,
                'document_category_id' => $categoryId,
                'objective' => $tpl['objective'] ?? null,
                'scope' => $tpl['scope'] ?? null,
                'references' => Arr::wrap($tpl['references'] ?? []),
                'terms' => Arr::wrap($tpl['terms'] ?? []),
                'responsibilities' => Arr::wrap($tpl['responsibilities'] ?? []),
                'procedure' => Arr::wrap($tpl['procedure'] ?? []),
                'records' => Arr::wrap($tpl['records'] ?? []),
                'annexes' => Arr::wrap($tpl['annexes'] ?? []),
                'data' => $tpl['data'] ?? [],
                'prepared_by' => $consultant?->id,
                'reviewed_by' => is_numeric($tpl['reviewed_by'] ?? null) ? (int)$tpl['reviewed_by'] : null,
                'approved_by' => is_numeric($tpl['approved_by'] ?? null) ? (int)$tpl['approved_by'] : null,
            ]);

            $document->save();

            if ($wasExisting) {
                $updated++;
            } else {
                $created++;
            }
        }

        $templateSlugs = array_values(array_unique($templateSlugs));
        $existingSlugs = empty($templateSlugs)
            ? []
            : Document::where('team_id', $team->id)
                ->whereIn('slug', $templateSlugs)
                ->pluck('slug')
                ->all();
        $existing = count($existingSlugs);
        $missingSlugs = array_values(array_diff($templateSlugs, $existingSlugs));

        Log::info('Resultado de populateDocumentTemplates', [
            'team_id' => $team->id,
            'templates_loaded' => count($templates),
            'template_slugs' => $templateSlugs,
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
            'existing_after_run' => $existing,
            'missing_after_run' => $missingSlugs,
        ]);

        if (! empty($missingSlugs)) {
            $missingHint = implode(', ', array_slice($missingSlugs, 0, 8));
            $reasonHint = empty($skipReasons) ? '' : ' Detalle: ' . implode('; ', array_slice($skipReasons, 0, 5));
            throw new \RuntimeException(
                'No se pudieron crear todas las plantillas. Faltan: ' . $missingHint . '.' . $reasonHint
            );
        }

        if ($existing === 0) {
            $hint = empty($skipReasons) ? '' : ' Detalle: ' . implode('; ', array_slice($skipReasons, 0, 5));
            throw new \RuntimeException(
                'No se crearon documentos desde document_templates.' . $hint
            );
        }
    }

    private function loadDocumentTemplates(): array
    {
        $fromConfig = config('document_templates.default_docs', []);
        $fromConfig = is_array($fromConfig) ? $fromConfig : [];

        $fromFile = [];
        $path = config_path('document_templates.php');
        if (is_file($path) && is_readable($path)) {
            $fromFile = $this->loadDocumentTemplatesFromDisk($path);

            // Fallback legacy include (por compatibilidad).
            if (empty($fromFile)) {
                if (function_exists('opcache_invalidate')) {
                    @opcache_invalidate($path, true);
                }
                clearstatcache(true, $path);

                $raw = include $path;
                if (is_array($raw) && is_array($raw['default_docs'] ?? null)) {
                    $fromFile = $raw['default_docs'];
                }
            }
        }

        Log::info('Carga de document_templates', [
            'from_config_count' => count($fromConfig),
            'from_file_count' => count($fromFile),
            'from_config_slugs' => $this->extractTemplateSlugs($fromConfig),
            'from_file_slugs' => $this->extractTemplateSlugs($fromFile),
            'path' => $path,
        ]);

        // Siempre priorizamos archivo para evitar config cache desactualizado.
        if (! empty($fromFile)) {
            return $fromFile;
        }

        return $fromConfig;
    }

    private function loadDocumentTemplatesFromDisk(string $path): array
    {
        try {
            $contents = file_get_contents($path);
            if (! is_string($contents) || trim($contents) === '') {
                return [];
            }

            // Evalua el contenido leido de disco para evitar bytecode stale de OPcache.
            $raw = (static function (string $phpCode) {
                return eval('?>' . $phpCode);
            })($contents);

            if (is_array($raw) && is_array($raw['default_docs'] ?? null)) {
                return $raw['default_docs'];
            }
        } catch (\Throwable $e) {
            Log::warning('No se pudo leer document_templates.php desde disco con eval', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
        }

        return [];
    }

    private function extractTemplateSlugs(array $templates): array
    {
        $slugs = [];

        foreach ($templates as $tpl) {
            if (! is_array($tpl)) {
                continue;
            }

            $slug = is_string($tpl['slug'] ?? null) ? trim($tpl['slug']) : '';
            if ($slug !== '') {
                $slugs[] = $slug;
            }
        }

        return array_values(array_unique($slugs));
    }

    private function resolveTemplateProcessId(Team $team, array $tpl): ?int
    {
        $code = is_string($tpl['process_id'] ?? null) ? trim($tpl['process_id']) : '';
        if ($code === '') {
            return null;
        }

        $process = Process::query()
            ->where('code', $code)
            ->where(function ($query) use ($team) {
                $query->whereNull('team_id')->orWhere('team_id', $team->id);
            })
            ->orderByRaw('CASE WHEN team_id = ? THEN 0 ELSE 1 END', [$team->id])
            ->first();

        if ($process) {
            return $process->id;
        }

        $defaults = config('processes_templates.default_processes', []);
        if (! is_array($defaults)) {
            return null;
        }

        $default = collect($defaults)->first(function ($item) use ($code) {
            return is_array($item) && (($item['code'] ?? null) === $code);
        });

        if (! is_array($default)) {
            return null;
        }

        $processTypeId = $this->resolveTemplateProcessTypeId($default, $code);
        if (! $processTypeId) {
            Log::warning('No se pudo crear proceso desde config: process_type_id no resoluble', [
                'process_code' => $code,
            ]);
            return null;
        }

        $created = Process::firstOrCreate(
            [
                'team_id' => $team->id,
                'code' => $code,
            ],
            [
                'process_type_id' => $processTypeId,
                'records' => Arr::wrap($default['records'] ?? []),
                'name' => $default['name'] ?? $code,
                'description' => $default['description'] ?? null,
                'suppliers' => Arr::wrap($default['suppliers'] ?? []),
                'inputs' => Arr::wrap($default['inputs'] ?? []),
                'procedures' => Arr::wrap($default['procedures'] ?? []),
                'outputs' => Arr::wrap($default['outputs'] ?? []),
                'clients' => Arr::wrap($default['clients'] ?? []),
            ]
        );

        return $created->id;
    }

    private function resolveTemplateProcessTypeId(array $processTemplate, string $processCode): ?int
    {
        $rawId = is_numeric($processTemplate['process_type_id'] ?? null)
            ? (int) $processTemplate['process_type_id']
            : null;

        if ($rawId && ProcessType::query()->whereKey($rawId)->exists()) {
            return $rawId;
        }

        $typeCode = strtoupper(substr($processCode, 0, 1));
        if ($typeCode === '') {
            return null;
        }

        $existing = ProcessType::query()->where('code', $typeCode)->first();
        if ($existing) {
            return $existing->id;
        }

        $defaults = config('process_types.process_types', []);
        if (! is_array($defaults)) {
            return null;
        }

        $default = collect($defaults)->first(function ($item) use ($typeCode) {
            return is_array($item)
                && strtoupper((string) ($item['code'] ?? '')) === $typeCode;
        });

        if (! is_array($default)) {
            return null;
        }

        $created = ProcessType::create([
            'name' => $default['name'] ?? $typeCode,
            'code' => $typeCode,
            'description' => $default['description'] ?? null,
        ]);

        return $created->id;
    }

    private function resolveTemplateCategoryId(Team $team, array $tpl): ?int
    {
        $code = is_string($tpl['document_category_id'] ?? null) ? trim($tpl['document_category_id']) : '';
        if ($code === '') {
            return null;
        }

        $category = DocumentCategory::query()
            ->where('code', $code)
            ->where(function ($query) use ($team) {
                $query->whereNull('team_id')->orWhere('team_id', $team->id);
            })
            ->orderByRaw('CASE WHEN team_id = ? THEN 0 ELSE 1 END', [$team->id])
            ->first();

        if ($category) {
            return $category->id;
        }

        $defaults = config('document_categories.document_caterogies', []);
        if (! is_array($defaults) || empty($defaults)) {
            $defaults = config('document_categories.document_categories', []);
        }

        if (! is_array($defaults)) {
            return null;
        }

        $default = collect($defaults)->first(function ($item) use ($code) {
            return is_array($item) && (($item['code'] ?? null) === $code);
        });

        if (! is_array($default)) {
            return null;
        }

        $created = DocumentCategory::firstOrCreate(
            [
                'team_id' => $team->id,
                'code' => $code,
            ],
            [
                'name' => $default['name'] ?? $code,
                'description' => $default['description'] ?? null,
                'data' => [],
            ]
        );

        return $created->id;
    }

    protected function populateTrainingSchedule(Team $team): void
    {
        $callable = config('training_schedule');
        if (! is_callable($callable)) {
            Log::warning('training_schedule no es callable en config');
            return;
        }

        $items = $callable(now());
        foreach ($items as $item) {
            $schedule = Schedule::create(array_merge($item, [
                'team_id' => $team->id,
                'user_id' => Auth::id(),
            ]));

            // Clonar como Event para visibilidad en calendario
            Event::create([
                'team_id' => $team->id,
                'user_id' => Auth::id(),
                'role_id' => null,
                'schedule_id' => $schedule->id,
                'title' => $schedule->name,
                'description' => $schedule->description ?? '',
                'type' => 'task',
                'start_date' => $schedule->starts_at ?? now(),
                'end_date' => $schedule->ends_at ?? now(),
                'has_time' => false,
            ]);
        }
    }
}
