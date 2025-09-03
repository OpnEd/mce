<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use App\Models\User;
use App\Models\ManagementIndicator;
use App\Models\MinutesIvcSection;
use App\Models\MinutesIvcSectionEntry;
use App\Models\Setting;
use App\Models\TenantSetting;
use App\Models\Document;
use App\Models\Process;
use App\Models\DocumentCategory;
use App\Models\Quality\Training\Enrollment;
use App\Models\Schedule;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Validation\ValidationException;
use App\Enums\PermissionType;
use App\Models\Quality\Training\Course;
use Database\Seeders\ManagementIndicatorTeamSeeder;
use Dom\Text;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class RegisterTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register team';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nombre de la compañía')->required(),
                TextInput::make('identification')->label('NIT')->required(),
                TextInput::make('address')->label('Dirección')->required(),
                TextInput::make('email')->label('E-mail')->email()->required(),
                TextInput::make('phonenumber')->label('Teléfono (fijo o celular)')->tel()->required(),
            ]);
    }

    /**
     * Maneja todo el proceso de registro en una transacción.
     *
     * @param array $data
     * @return Team
     * @throws \Throwable
     */
    protected function handleRegistration(array $data): Team
    {
        return DB::transaction(function () use ($data) {
            $team = $this->createTeam($data);

            $consultant = $this->getConsultant();

            $this->attachUsersToTeam($team, Auth::user(), $consultant);

            [$roleAdmin, $roleConsultant] = $this->createRoles($team);

            // Configura Spatie para que trabaje por team
            app(PermissionRegistrar::class)->setPermissionsTeamId($team->id);

            $this->assignRoles($team, Auth::user(), $consultant, $roleAdmin, $roleConsultant);

            $this->populateManagementIndicators($team, $roleAdmin);

            $this->populateIvcSectionsAndEntries($team);

            $this->createPermissionsAndSyncRole($team, $roleAdmin);

            $this->createTenantSettingsFromConfig($team);

            $this->populateDocumentsFromConfig($team, $consultant);

            $this->populateTrainingSchedule($team, $roleAdmin);

            $this->enrollInitialCourse($team);

            // Opcional: emitir evento para listeners / jobs pesadas
            // event(new \App\Events\TeamCreated($team));

            return $team;
        });
    }

    private function createTeam(array $data): Team
    {
        // Validaciones extra opcionales
        return Team::create($data);
    }

    private function getConsultant(): ?User
    {
        $consultantId = config('app.default_consultant_id', 1);
        return User::find($consultantId);
    }

    private function attachUsersToTeam(Team $team, User $owner, ?User $consultant): void
    {
        $team->users()->syncWithoutDetaching([
            $owner->id,
            $consultant?->id,
        ]);
    }

    /**
     * Crea (o recupera) roles scoped al team.
     * @return array [Role $adminRole, Role $consultantRole]
     */
    private function createRoles(Team $team): array
    {
        $admin = Role::firstOrCreate(
            ['name' => 'Administrador', 'guard_name' => 'web', 'team_id' => $team->id]
        );

        $consultant = Role::firstOrCreate(
            ['name' => 'Consultor', 'guard_name' => 'web', 'team_id' => $team->id]
        );

        return [$admin, $consultant];
    }

    private function assignRoles(Team $team, User $user, ?User $consultant, Role $adminRole, Role $consultantRole): void
    {
        // Asignar roles sobre modelos (no sobre IDs)
        $user->assignRole($adminRole);
        if ($consultant) {
            $consultant->assignRole($consultantRole);
        }
    }

    /**
     * Pobla management indicators relacionando existing indicators según config
     */
    private function populateManagementIndicators(Team $team, Role $roleAdmin): void
    {
        $indicatorNames = config('management-indicators', []);

        // Recuperar indicadores existentes por nombre en una sola query
        $indicators = ManagementIndicator::whereIn('name', $indicatorNames)->get()->keyBy('name');

        foreach ($indicatorNames as $name) {
            $indicator = $indicators->get($name);
            if (! $indicator) {
                Log::warning("ManagementIndicator no hallado para name='{$name}'");
                continue;
            }

            $team->managementIndicators()
                ->syncWithoutDetaching([
                    $indicator->id => [
                        'role_id' => $roleAdmin->id,
                        'periodicity' => 'Mensual',
                        'indicator_goal' => $indicator->indicator_goal,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
        }
    }

    /**
     * Pobla las secciones de IVC y sus entradas según config.
     */
    private function populateIvcSectionsAndEntries(Team $team): void
    {
        $sections = config('minutes-ivc-sections', []);
        if (! is_array($sections)) return;

        // Crear/actualizar secciones (idempotente)
        foreach ($sections as $s) {
            MinutesIvcSection::updateOrCreate(
                [
                    'team_id' => $team->id,
                    'order' => $s['order'] ?? null,
                    'slug' => $s['slug'] ?? null,
                ],
                [
                    'name' => $s['name'] ?? null,
                    'description' => $s['description'] ?? null,
                    'status' => $s['status'] ?? null,
                ]
            );
        }

        // Mapeo: variable => archivo de entradas
        $sectionConfigMap = [
            'Recurso Humano'                    => 'minutes-ivc-second-section-entries',
            'Infraestructura Física'            => 'minutes-ivc-third-section-entries',
            'Saneamiento de edificaciones'      => 'minutes-ivc-fourth-section-entries',
            'Áreas'                             => 'minutes-ivc-fifth-section-entries',
            'Clasificación del Establecimiento' => 'minutes-ivc-sixth-section-entries',
            'Servicios Ofrecidos'               => 'minutes-ivc-seventh-section-entries',
            'Inyectología'                      => 'minutes-ivc-inyectologia-section-entries',
            'Otros aspectos'                    => 'minutes-ivc-eighth-section-entries',
            'Sistema de gestión de calidad'     => 'minutes-ivc-nine-section-entries',
            ' Proceso de Selección'             => 'minutes-ivc-tenth-section-entries',
            ' Proceso de Adquisición'           => 'minutes-ivc-eleventh-section-entries',
            ' Proceso de Recepción'             => 'minutes-ivc-twelveth-section-entries',
            ' Proceso de Almacenamiento'        => 'minutes-ivc-thirteenth-section-entries',
            ' Proceso de Dispensación'          => 'minutes-ivc-fourteenth-section-entries',
            ' Proceso de Devoluciones'          => 'minutes-ivc-fifteenth-section-entries',
            ' Proceso de Manejo de Medicamentos Cadena de Frío' => 'minutes-ivc-sixteenth-section-entries',
        ];

        // Poblar entradas para cada sección
        foreach ($sectionConfigMap as $sectionName => $configKey) {
            $section = MinutesIvcSection::where('team_id', $team->id)
                ->where('name', $sectionName)
                ->first();

            if (! $section) {
                Log::warning("Sección IVC no encontrada: {$sectionName} (team {$team->id})");
                continue;
            }

            $entries = config($configKey, []);
            if (! is_array($entries)) continue;

            foreach ($entries as $e) {
                MinutesIvcSectionEntry::updateOrCreate(
                    [
                        'minutes_ivc_section_id' => $section->id,
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
    }

    /**
     * Crea permisos base y sincroniza al rol administrador.
     */
    private function createPermissionsAndSyncRole(Team $team, Role $roleAdmin): void
    {
        $permissionNames = [];

        foreach (PermissionType::cases() as $permissionType) {
            // firstOrCreate por team_id si tu tabla la tiene
            $perm = Permission::firstOrCreate([
                'name'       => $permissionType->value,
                'guard_name' => 'web',
                'team_id'    => $team->id,
            ]);

            if (array_key_exists('label', $perm->getAttributes())) {
                $perm->label = $permissionType->getLabel();
                $perm->save();
            }

            $permissionNames[] = $perm->name;
        }

        $roleAdmin->syncPermissions($permissionNames);
    }

    /**
     * Crea TenantSettings (misión/visión/política) desde config
     */
    private function createTenantSettingsFromConfig(Team $team): void
    {
        $cfg = config('tenant_settings', []);
        $mission = $cfg['mission'] ?? null;
        $vision  = $cfg['vision'] ?? null;
        $policy  = $cfg['quality_policy'] ?? [];

        $policyText = $policy['statement'] ?? null;
        $policyData = [
            'objectives' => $policy['objectives'] ?? [],
            'commitments' => $policy['commitments'] ?? [],
        ];

        $allSettingIds = Setting::pluck('id');

        foreach ($allSettingIds as $settingId) {
            switch ($settingId) {
                case 1:
                    $value = $mission;
                    $data = null;
                    break;
                case 2:
                    $value = $vision;
                    $data = null;
                    break;
                case 3:
                    $value = $policyText;
                    $data = $policyData;
                    break;
                default:
                    $value = null;
                    $data = null;
            }

            TenantSetting::updateOrCreate(
                [
                    'team_id' => $team->id,
                    'setting_id' => $settingId,
                ],
                [
                    'value' => $value,
                    'data'  => $data,
                    'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Crea o actualiza documentos a partir de plantillas config.
     */
    private function populateDocumentsFromConfig(Team $team, ?User $consultant): void
    {
        $templates = config('document_templates.default_docs', []);
        if (! is_array($templates) || empty($templates)) return;

        foreach ($templates as $tpl) {
            $processId = Process::where('code', $tpl['process_id'] ?? null)->value('id');
            $categoryId = DocumentCategory::where('code', $tpl['document_category_id'] ?? null)->value('id');

            if (! $processId || ! $categoryId) {
                Log::warning("Plantilla de documento: tipo o proceso no hallado", $tpl);
                continue;
            }

            Document::updateOrCreate(
                [
                    'team_id' => $team->id,
                    'slug' => $tpl['slug'],
                ],
                [
                    'title' => $tpl['title'] ?? null,
                    'sequence' => 0,
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
                    'prepared_by' => $consultant?->id,
                    'reviewed_by' => is_numeric($tpl['reviewed_by'] ?? null) ? (int)$tpl['reviewed_by'] : null,
                    'approved_by' => is_numeric($tpl['approved_by'] ?? null) ? (int)$tpl['approved_by'] : null,
                    'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Crea cronograma de capacitación y eventos asociados.
     */
    private function populateTrainingSchedule(Team $team, Role $roleAdmin): void
    {
        $callable = config('training_schedule');
        if (! is_callable($callable)) {
            Log::warning("training_schedule config no es callable");
            return;
        }

        $items = $callable(now());
        foreach ($items as $item) {
            $schedule = Schedule::create(array_merge($item, [
                'team_id' => $team->id,
                'user_id' => Auth::id(),
            ]));

            Event::create([
                'team_id' => $team->id,
                'user_id' => Auth::id(),
                'role_id' => $roleAdmin->id,
                'schedule_id' => $schedule->id,
                'title' => $schedule->name,
                'description' => $schedule->description,
                'type' => 'task',
                'start_date' => $schedule->starts_at,
                'end_date' => $schedule->ends_at,
                'has_time' => false,
                'start_time' => null,
                'end_time' => null,
            ]);
        }
    }

    /**
     * Matricula inicial en curso por defecto (si existe).
     */
    private function enrollInitialCourse(Team $team): void
    {
        $courseTitle = '¿Cómo pasar la visita de la Secretaría de Salud?';
        $course = Course::where('title', $courseTitle)->first();

        if (! $course) {
            Log::warning("Curso inicial no encontrado: {$courseTitle}");
            return;
        }

        // Evitar duplicados
        Enrollment::firstOrCreate([
            'team_id' => $team->id,
            'user_id' => Auth::id(),
            'course_id' => $course->id,
        ], [
            'status' => 'in_progress',
            'progress' => 0,
            'started_at' => null,
            'completed_at' => null,
            'last_accessed_at' => null,
            'certificated_at' => null,
            'certificate_url' => null,
            'score_final' => null,
        ]);
    }
}
