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

use App\Models\Team;
use App\Models\ManagementIndicator;
use App\Models\MinutesIvcSection;
use App\Models\MinutesIvcSectionEntry;
use App\Models\Document;
use App\Models\Process;
use App\Models\DocumentCategory;
use App\Models\Schedule;
use App\Models\Event;
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
            'minutes-ivc-first-section-entries'     => 'Entradas IVC - Sección 1',
            'minutes-ivc-second-section-entries'    => 'Entradas IVC - Sección 2',
            'minutes-ivc-third-section-entries'    => 'Entradas IVC - Sección 3',
            'minutes-ivc-fourth-section-entries'    => 'Entradas IVC - Sección 4',
            'minutes-ivc-fifth-section-entries'    => 'Entradas IVC - Sección 5',
            'minutes-ivc-sixth-section-entries'    => 'Entradas IVC - Sección 6',
            'minutes-ivc-seventh-section-entries'    => 'Entradas IVC - Sección 7',
            'minutes-ivc-eighth-section-entries'    => 'Entradas IVC - Sección 8',
            'minutes-ivc-nine-section-entries'    => 'Entradas IVC - Sección 9',
            'minutes-ivc-tenth-section-entries'    => 'Entradas IVC - Sección 10',
            'minutes-ivc-eleventh-section-entries'    => 'Entradas IVC - Sección 11',
            'minutes-ivc-twelveth-section-entries'    => 'Entradas IVC - Sección 12',
            'minutes-ivc-thirteenth-section-entries'    => 'Entradas IVC - Sección 13',
            'minutes-ivc-fourteenth-section-entries'    => 'Entradas IVC - Sección 14',
            'minutes-ivc-fifteenth-section-entries'    => 'Entradas IVC - Sección 15',
            'minutes-ivc-sixteenth-section-entries'    => 'Entradas IVC - Sección 16',
            'minutes-ivc-inyectologia-section-entries'    => 'Entradas IVC - Inyectología',
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
            case 'minutes-ivc-tenth-section-entries':
            case 'minutes-ivc-eleventh-section-entries':
            case 'minutes-ivc-twelveth-section-entries':
            case 'minutes-ivc-thirteenth-section-entries':
            case 'minutes-ivc-fourteenth-section-entries':
            case 'minutes-ivc-fifteenth-section-entries':
            case 'minutes-ivc-sixteenth-section-entries':
            case 'minutes-ivc-inyectologia-section-entries':
                // Para entradas de secciones usamos el mismo método que recibe el configKey
                $this->populateIvcSectionEntries($team, $configKey);
                break;

            case 'document_templates.default_docs':
                $this->populateDocumentTemplates($team);
                break;

            case 'training_schedule':
                $this->populateTrainingSchedule($team);
                break;

            default:
                throw new \InvalidArgumentException("Clave de configuración no reconocida: {$configKey}");
        }
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
            MinutesIvcSection::updateOrCreate(
                [
                    'team_id' => $team->id,
                    'order'   => $s['order'] ?? null,
                    'slug'    => $s['slug'] ?? null,
                ],
                [
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
        $entries = config($configKey, []);
        if (! is_array($entries) || empty($entries)) return;
        
        // Mapeo de prefijos de entry_id a nombre de sección
        $sectionPrefixes = [
            '0.'  => 'Cédula del establecimiento',
            '2.'  => 'Recurso Humano',
            '3.'  => 'Infraestructura Física',
            '4.'  => 'Saneamiento de edificaciones',
            '5.'  => 'Áreas',
            '6.'  => 'Clasificación del Establecimiento',
            '7.'  => 'Servicios Ofrecidos',
            '8.'  => 'Otros aspectos',
            '9.'  => 'Sistema de gestión de calidad',
            '10.' => ' Proceso de Selección',
            '11.' => ' Proceso de Adquisición',
            '12.' => ' Proceso de Recepción',
            '13.' => ' Proceso de Almacenamiento',
            '14.' => ' Proceso de Dispensación',
            '15.' => ' Proceso de Devoluciones',
            '16.' => ' Proceso de Manejo de Medicamentos Cadena de Frío',
            'I'   => 'Inyectología',
        ];
        
        foreach ($entries as $e) {
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
            //dd($sectionId);

            if (! $sectionId) {
                Log::warning('No se pudo determinar minutes_ivc_section_id para entrada', ['entry' => $e]);
                continue;
            }

            MinutesIvcSectionEntry::updateOrCreate(
                [
                    'minutes_ivc_section_id' => $sectionId,
                    'entry_id' => $e['entry_id'] ?? null,
                    'question' => $e['question'] ?? null,
                ],
                [
                    'apply' => $e['apply'] ?? true,
                    'criticality' => $e['criticality'] ?? null,
                    'answer' => $e['answer'] ?? null,
                    'entry_type' => $e['entry_type'] ?? null,
                    'links' => $e['links'] ?? null,
                    'compliance' => $e['compliance'] ?? null,
                ]
            );
        }
    }

    protected function populateDocumentTemplates(Team $team): void
    {
        $templates = config('document_templates.default_docs', []);
        if (! is_array($templates)) return;

        foreach ($templates as $tpl) {
            $processId = Process::where('code', $tpl['process_id'] ?? null)->value('id');
            $categoryId = DocumentCategory::where('code', $tpl['document_category_id'] ?? null)->value('id');

            if (! $processId || ! $categoryId) {
                Log::warning('Plantilla: proceso o category no encontrados', ['tpl' => $tpl]);
                continue;
            }

            Document::updateOrCreate(
                [
                    'team_id' => $team->id,
                    'slug' => $tpl['slug'],
                ],
                [
                    'title' => $tpl['title'] ?? null,
                    'sequence' => $tpl['sequence'] ?? 0,
                    'process_id' => $processId,
                    'document_category_id' => $categoryId,
                    'objective' => $tpl['objective'] ?? null,
                    'scope' => $tpl['scope'] ?? null,
                    'references' => $tpl['references'] ?? [],
                    'terms' => $tpl['terms'] ?? [],
                    'responsibilities' => $tpl['responsibilities'] ?? [],
                    'procedure' => $tpl['procedure'] ?? [],
                    'records' => $tpl['records'] ?? [],
                    'annexes' => $tpl['annexes'] ?? [],
                    'data' => $tpl['data'] ?? [],
                    'prepared_by' => $tpl['prepared_by'] ?? null,
                    'reviewed_by' => $tpl['reviewed_by'] ?? null,
                    'approved_by' => $tpl['approved_by'] ?? null,
                    'updated_at' => now(),
                ]
            );
        }
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
