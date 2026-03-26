<?php

namespace App\Services;

use App\Enums\PermissionType;
use App\Models\Quality\Training\Course;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Quality\Training\Enrollment;
use App\Models\Event;
use App\Models\ManagementIndicator;
use App\Models\Quality\ManagementIndicatorTeam;
use App\Models\MinutesIvcSection;
use App\Models\MinutesIvcSectionEntry;
use App\Models\Process;
use App\Models\ProcessType;
use App\Models\Quality\QualityGoal;
use App\Models\Quality\Records\Cleaning\CleaningImplement;
use App\Models\Quality\Records\Cleaning\Desinfectant;
use App\Models\Quality\Records\Cleaning\StablishmentArea;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\Team;
use App\Models\TenantSetting;
use App\Models\User;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class TeamSetupService
{
    public function getPopulateConfigOptions(): array
    {
        return [
            'General' => [
                'management-indicators' => 'Indicadores de gestión',
                'minutes-ivc-sections' => 'Secciones IVC (estructura)',
                'minutes-ivc-second-section-entries' => 'Entradas IVC - Sección 2',
                'minutes-ivc-third-section-entries' => 'Entradas IVC - Sección 3',
                'minutes-ivc-fourth-section-entries' => 'Entradas IVC - Sección 4',
                'minutes-ivc-fifth-section-entries' => 'Entradas IVC - Sección 5',
                'minutes-ivc-sixth-section-entries' => 'Entradas IVC - Sección 6',
                'minutes-ivc-seventh-section-entries' => 'Entradas IVC - Sección 7',
                'minutes-ivc-eighth-section-entries' => 'Entradas IVC - Sección 8',
                'minutes-ivc-nine-section-entries' => 'Entradas IVC - Sección 9',
                'processes_templates.default_processes' => 'Plantillas de caracterización de Procesos',
                'document_templates.default_docs' => 'Plantillas de documentos',
                'training_schedule' => 'Cronograma de capacitación',
                'cleaning_schedule' => 'Cronograma de limpieza',
                'equipment_calibration_schedule' => 'Cronograma de calibración de equipos',
                'internal_audit_schedule' => 'Cronograma de Auditorías internas',
                'ethical_values' => 'Valores',
                'tenant_settings' => 'Misión, Visión, Política de Calidad',
            ],
            'Limpieza' => [
                'stablishment_areas' => 'Áreas del establecimiento',
                'cleaning_implements' => 'Implementos de limpieza',
                'desinfectants' => 'Desinfectantes',
            ],
        ];
    }

    public function populateByConfigKey(Team $team, string $configKey, ?User $actor = null): void
    {
        $consultant = $this->getConsultant();
        [$roleAdmin] = $this->createRoles($team);

        switch ($configKey) {
            case 'management-indicators':
                $this->populateManagementIndicators($team, $roleAdmin);
                break;
            case 'minutes-ivc-sections':
                $this->populateIvcSections($team);
                break;
            case 'minutes-ivc-second-section-entries':
            case 'minutes-ivc-third-section-entries':
            case 'minutes-ivc-fourth-section-entries':
            case 'minutes-ivc-fifth-section-entries':
            case 'minutes-ivc-sixth-section-entries':
            case 'minutes-ivc-seventh-section-entries':
            case 'minutes-ivc-eighth-section-entries':
            case 'minutes-ivc-nine-section-entries':
                $this->populateIvcSectionEntries($team, $configKey);
                break;
            case 'processes_templates.default_processes':
                $this->populateProcessesTemplatesFromConfig($team);
                break;
            case 'document_templates.default_docs':
                $this->populateDocumentsFromConfig($team, $consultant);
                break;
            case 'ethical_values':
                $this->populateValuesSetting($team);
                break;
            case 'tenant_settings':
                $this->createTenantSettingsFromConfig($team);
                break;
            case 'training_schedule':
            case 'cleaning_schedule':
            case 'equipment_calibration_schedule':
            case 'internal_audit_schedule':
                $this->populateScheduleByKey($team, $configKey, $actor, $roleAdmin);
                break;
            case 'stablishment_areas':
                $this->populateStablishmentAreas($team);
                break;
            case 'cleaning_implements':
                $this->populateCleaningImplements($team);
                break;
            case 'desinfectants':
                $this->populateDesinfectants($team);
                break;
            default:
                throw new \InvalidArgumentException("Clave de configuración no reconocida: {$configKey}");
        }
    }

    public function setupTeam(Team $team, User $owner): void
    {
        $consultant = $this->getConsultant();

        /* if ($consultant) {
            $team->users()->syncWithoutDetaching([$consultant->id]);
        } */

        [$roleAdmin, $roleConsultant] = $this->createRoles($team);

        app(PermissionRegistrar::class)->setPermissionsTeamId($team->id);

        $this->assignRoles(
            $owner,
            //$consultant, 
            $roleAdmin,
            //$roleConsultant
        );

        $this->createPermissionsAndSyncRole($team, $roleAdmin);
        $this->populateManagementIndicators($team, $roleAdmin);
        $this->populateIvcSectionsAndEntries($team);
        $this->createTenantSettingsFromConfig($team);
        $this->populateStablishmentAreas($team);
        $this->populateCleaningImplements($team);
        $this->populateDesinfectants($team);
        $this->populateProcessesTemplatesFromConfig($team);
        $this->populateDocumentsFromConfig($team, $consultant);
        $this->populateSchedules($team, $owner, $roleAdmin);
        $this->enrollInitialCourse($team, $owner);
    }

    private function getConsultant(): ?User
    {
        $consultantId = env('CONSULTANT_ID', 1);
        return User::find($consultantId);
    }

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

    private function assignRoles(
        User $user,
        //?User $consultant, 
        Role $adminRole,
        //?Role $consultantRole
    ): void {
        $user->assignRole($adminRole);
        /* if ($consultant && $consultantRole) {
            $consultant->assignRole($consultantRole);
        } */
    }

    public function populateManagementIndicators(Team $team, Role $roleAdmin): void
    {
        $entries = $this->normalizeManagementIndicatorConfig(config('management-indicators', []));
        if (empty($entries)) {
            return;
        }

        $names = array_values(array_unique(array_column($entries, 'name')));
        $indicators = ManagementIndicator::query()
            ->whereIn('name', $names)
            ->get()
            ->keyBy('name');

        foreach ($entries as $entry) {
            $name = $entry['name'];
            $indicator = $indicators->get($name);

            if (! $indicator) {
                $createData = $this->buildManagementIndicatorCreateData($entry);
                if (! $createData) {
                    Log::warning("ManagementIndicator no hallado y no se pudo crear: {$name}");
                    continue;
                }

                $indicator = ManagementIndicator::create($createData);
                $indicators->put($name, $indicator);
            }

            $defaultGoal = $this->normalizeIndicatorGoal($entry['indicator_goal'] ?? null);

            $pivot = ManagementIndicatorTeam::query()
                ->where('team_id', $team->id)
                ->where('management_indicator_id', $indicator->id)
                ->first();

            if (! $pivot) {
                ManagementIndicatorTeam::create([
                    'team_id' => $team->id,
                    'management_indicator_id' => $indicator->id,
                    'role_id' => $roleAdmin->id,
                    'periodicity' => $entry['periodicity'] ?? 'Mensual',
                    'indicator_goal' => $defaultGoal,
                ]);
                continue;
            }

            $updates = [];
            if (! $pivot->role_id) {
                $updates['role_id'] = $roleAdmin->id;
            }
            if (! $pivot->periodicity) {
                $updates['periodicity'] = $entry['periodicity'] ?? 'Mensual';
            }
            if ($pivot->indicator_goal === null && $defaultGoal !== null) {
                $updates['indicator_goal'] = $defaultGoal;
            }

            if (! empty($updates)) {
                $pivot->fill($updates);
                $pivot->save();
            }
        }
    }

    private function normalizeManagementIndicatorConfig(array $raw): array
    {
        $entries = [];

        foreach ($raw as $key => $value) {
            $entry = [];

            if (is_int($key)) {
                if (is_string($value)) {
                    $entry['name'] = $value;
                } elseif (is_array($value)) {
                    $entry = $value;
                }
            } else {
                $entry['name'] = (string) $key;
                if (is_array($value)) {
                    $entry = array_merge($entry, $value);
                } else {
                    $entry['indicator_goal'] = $value;
                }
            }

            if (! isset($entry['name']) && isset($entry['indicator'])) {
                $entry['name'] = $entry['indicator'];
            }

            $name = trim((string) ($entry['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $entry['name'] = $name;

            if (! array_key_exists('indicator_goal', $entry)) {
                if (array_key_exists('goal', $entry)) {
                    $entry['indicator_goal'] = $entry['goal'];
                } elseif (array_key_exists('meta', $entry)) {
                    $entry['indicator_goal'] = $entry['meta'];
                }
            }

            $entries[$name] = $entry;
        }

        return array_values($entries);
    }

    private function buildManagementIndicatorCreateData(array $entry): ?array
    {
        $name = $entry['name'] ?? null;
        if (! is_string($name) || trim($name) === '') {
            return null;
        }

        $defaults = $this->defaultManagementIndicatorPayloads();
        $payload = array_merge($defaults[$name] ?? [], $entry);

        $qualityGoalId = $payload['quality_goal_id'] ?? null;
        if (! $qualityGoalId && isset($payload['quality_goal'])) {
            $qualityGoalId = QualityGoal::query()
                ->where('name', $payload['quality_goal'])
                ->value('id');
        }
        if (! $qualityGoalId) {
            $qualityGoalId = QualityGoal::query()->value('id');
        }
        if (! $qualityGoalId) {
            Log::warning("ManagementIndicator '{$name}' sin quality_goal_id");
            return null;
        }

        $objective = trim((string) ($payload['objective'] ?? ''));
        $description = trim((string) ($payload['description'] ?? ''));
        $numerator = trim((string) ($payload['numerator'] ?? ''));

        if ($objective === '') {
            $objective = "Indicador {$name}.";
        }
        if ($description === '') {
            $description = "Indicador {$name}.";
        }
        if ($numerator === '') {
            $numerator = "# de registros de {$name}";
        }

        $type = $payload['type'] ?? null;
        if (! in_array($type, ['Cardinal', 'Porcentual'], true)) {
            $type = null;
        }

        $denominator = $payload['denominator'] ?? null;
        $denominator = is_numeric($denominator) ? (int) $denominator : null;

        return [
            'quality_goal_id' => $qualityGoalId,
            'name' => $name,
            'objective' => $objective,
            'description' => $description,
            'type' => $type,
            'information_source' => $payload['information_source'] ?? null,
            'numerator' => $numerator,
            'denominator' => $denominator,
            'denominator_description' => $payload['denominator_description'] ?? null,
        ];
    }

    private function normalizeIndicatorGoal(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        return null;
    }

    private function defaultManagementIndicatorPayloads(): array
    {
        return [
            'Selección' => [
                'quality_goal_id' => 1,
                'objective' => 'Vigilar incumplimientos en la disponibilidad por fallas en la selección.',
                'description' => '# de productos faltantes por considerarse de baja rotación o alto costo (productos no seleccionados), no se incluyen productos descontinuados, agotados en el mercado, o que por cualquier otra razón ajena a la responsabilidad de la droguería no se hallan disponibles.',
                'type' => 'Cardinal',
                'information_source' => 'Registros de faltantes.',
                'numerator' => '# de faltantes de baja rotación',
                'denominator' => null,
                'denominator_description' => null,
            ],
            'Adquisición' => [
                'quality_goal_id' => 1,
                'objective' => 'Vigilar incumplimientos en la disponibilidad debidos a fallas en el proceso de adquisición.',
                'description' => '# de productos faltantes por fallas en el proceso de adquisición. Productos de alta rotación que el usuario no encuentra.',
                'type' => 'Cardinal',
                'information_source' => 'Registros de faltantes.',
                'numerator' => '# de faltantes de alta rotación',
                'denominator' => null,
                'denominator_description' => null,
            ],
            'Recepción' => [
                'quality_goal_id' => 2,
                'objective' => 'Vigilar la realización de recepción técnica de todos los productos que ingresan al establecimiento.',
                'description' => 'Proporción de envíos por parte de los proveedores a los que se les realiza la recepción técnica.',
                'type' => 'Porcentual',
                'information_source' => 'Registro de órdenes de compra y registro de recepción técnica.',
                'numerator' => '# de recepciones técnicas',
                'denominator' => null,
                'denominator_description' => '# de órdenes de compra',
            ],
            'Almacenamiento - VAMT' => [
                'quality_goal_id' => 2,
                'objective' => 'Vigilar el monitoreo diario de variables ambientales.',
                'description' => 'Variables Ambientales Medidas a Tiempo - Cumplimiento con la obligación de verificar, como mínimo, dos veces al día (mañana y tarde-noche), que la temperatura y la humedad se encuentren dentro de los rangos permitidos.',
                'type' => 'Cardinal',
                'information_source' => 'Registros de temperatura y humedad.',
                'numerator' => 'sumatoria de registros diarios en la mañana y de registros diarios en la tarde-noche',
                'denominator' => 30,
                'denominator_description' => '# de días en el mes',
            ],
            'Almacenamiento - VADR' => [
                'quality_goal_id' => 2,
                'objective' => 'Vigilar la permanencia de variables ambientales dentro de rangos permitidos.',
                'description' => 'Variables Ambientales Dentro de Rango - Se calcula la proporción del tiempo que las variables ambientales se encuentran dentro de los rangos permitidos.',
                'type' => 'Porcentual',
                'information_source' => 'Registros de temperatura y humedad.',
                'numerator' => '# de registros que indican desviación',
                'denominator' => null,
                'denominator_description' => '# total de registros',
            ],
            'Almacenamiento' => [
                'quality_goal_id' => 2,
                'objective' => 'Vigilar la permanencia de variables ambientales dentro de rangos permitidos.',
                'description' => 'Variables Ambientales Dentro de Rango - Se calcula la proporción del tiempo que las variables ambientales se encuentran dentro de los rangos permitidos.',
                'type' => 'Porcentual',
                'information_source' => 'Registros de temperatura y humedad.',
                'numerator' => '# de registros que indican desviación',
                'denominator' => null,
                'denominator_description' => '# total de registros',
            ],
            'Devoluciones' => [
                'quality_goal_id' => 6,
                'objective' => 'Vigilar devoluciones por fallas en los procesos de la droguería (vencimiento o deterioro durante el almacenamiento).',
                'description' => '# de productos que es necesario devolver al proveedor, o descartarlos, debido al acercamiento o cumplimiento de la fecha de vencimiento, o por deterioro atribuible a malas prácticas de manejo o almacenamiento.',
                'type' => 'Cardinal',
                'information_source' => 'Registro de devoluciones y descartes.',
                'numerator' => '# de productos devueltos o descartados',
                'denominator' => null,
                'denominator_description' => null,
            ],
            'Dispensación - PUR' => [
                'quality_goal_id' => 5,
                'objective' => 'Vigilar que se realice la promoción del uso racional de los medicamentos priorizados (venta con fórmula médica).',
                'description' => 'Promoción del Uso Racional - Frecuencia con que se brinda información sobre el uso racional de medicamentos.',
                'type' => 'Cardinal',
                'information_source' => 'Registros de promoción del uso adecuado de medicamentos.',
                'numerator' => '# de actividades de promoción del uso racional documentadas',
                'denominator' => null,
                'denominator_description' => null,
            ],
            'Dispensación - SU' => [
                'quality_goal_id' => 5,
                'objective' => 'Vigilar que los usuarios se sientan atendidos con cordialidad, humanidad, efectividad y con productos seguros a precios razonables.',
                'description' => 'Satisfacción del Usuario - Encuesta de satisfacción a los usuarios que evalúa distintas áreas y aspectos del establecimiento.',
                'type' => 'Cardinal',
                'information_source' => 'Encuestas de satisfacción del usuario.',
                'numerator' => 'puntaje promedio en la pregunta de satisfacción en general',
                'denominator' => null,
                'denominator_description' => null,
            ],
            'Dispensación' => [
                'quality_goal_id' => 5,
                'objective' => 'Vigilar el desempeño del proceso de dispensación (uso racional y satisfacción del usuario).',
                'description' => 'Frecuencia con que se brinda información sobre el uso racional de medicamentos y el nivel de satisfacción del usuario con el servicio.',
                'type' => 'Cardinal',
                'information_source' => 'Registros de dispensación y encuestas de satisfacción del usuario.',
                'numerator' => '# de actividades de dispensación documentadas',
                'denominator' => null,
                'denominator_description' => null,
            ],
        ];
    }

    public function populateIvcSections(Team $team): void
    {
        $sections = config('minutes-ivc-sections', []);
        if (!is_array($sections)) {
            return;
        }

        foreach ($sections as $s) {
            MinutesIvcSection::updateOrCreate(
                [
                    'team_id' => $team->id,
                    'slug' => $s['slug'] ?? null,
                ],
                [
                    'order' => $s['order'] ?? null,
                    'route' => $s['route'] ?? null,
                    'name' => $s['name'] ?? null,
                    'description' => $s['description'] ?? null,
                    'status' => $s['status'] ?? null,
                ]
            );
        }
    }

    public function populateIvcSectionEntries(Team $team, string $configKey): void
    {
        $sectionOrderByConfig = [
            'minutes-ivc-second-section-entries' => 2,
            'minutes-ivc-third-section-entries' => 3,
            'minutes-ivc-fourth-section-entries' => 4,
            'minutes-ivc-fifth-section-entries' => 5,
            'minutes-ivc-sixth-section-entries' => 6,
            'minutes-ivc-seventh-section-entries' => 7,
            'minutes-ivc-eighth-section-entries' => 8,
            'minutes-ivc-nine-section-entries' => 9,
        ];

        $order = $sectionOrderByConfig[$configKey] ?? null;
        if (!$order) {
            Log::warning("Config de entradas IVC no reconocida: {$configKey}");
            return;
        }

        $section = MinutesIvcSection::query()
            ->where('team_id', $team->id)
            ->where('order', $order)
            ->first();

        if (!$section) {
            Log::warning("Sección IVC no encontrada para order={$order} (team {$team->id})");
            return;
        }

        $rawEntries = config($configKey, []);
        $entries = $this->flattenMinutesIvcEntries(is_array($rawEntries) ? $rawEntries : []);

        foreach ($entries as $e) {
            if (empty($e['entry_id'])) {
                Log::warning("Entrada IVC sin entry_id", ['config' => $configKey, 'entry' => $e]);
                continue;
            }

            MinutesIvcSectionEntry::updateOrCreate(
                ['minutes_ivc_section_id' => $section->id, 'entry_id' => $e['entry_id']],
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

    public function populateIvcSectionsAndEntries(Team $team): void
    {
        $this->populateIvcSections($team);

        $sectionConfigByOrder = [
            2 => 'minutes-ivc-second-section-entries',
            3 => 'minutes-ivc-third-section-entries',
            4 => 'minutes-ivc-fourth-section-entries',
            5 => 'minutes-ivc-fifth-section-entries',
            6 => 'minutes-ivc-sixth-section-entries',
            7 => 'minutes-ivc-seventh-section-entries',
            8 => 'minutes-ivc-eighth-section-entries',
            9 => 'minutes-ivc-nine-section-entries',
        ];

        foreach ($sectionConfigByOrder as $configKey) {
            $this->populateIvcSectionEntries($team, $configKey);
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

    public function populateStablishmentAreas(Team $team): void
    {
        $areas = config('stablishment_areas', []);
        if (!is_array($areas)) {
            return;
        }

        foreach ($areas as $area) {
            if (!is_array($area)) {
                continue;
            }

            $name = trim((string) ($area['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            StablishmentArea::updateOrCreate(
                [
                    'team_id' => $team->id,
                    'name' => $name,
                ],
                [
                    'description' => $area['description'] ?? null,
                    'type' => $area['type'] ?? null,
                    'frequency' => $area['frequency'] ?? null,
                    'active' => $area['active'] ?? true,
                ]
            );
        }
    }

    public function populateCleaningImplements(Team $team): void
    {
        $implements = config('cleaning_implements', []);
        if (!is_array($implements)) {
            return;
        }

        $areasByName = StablishmentArea::query()
            ->where('team_id', $team->id)
            ->pluck('id', 'name');

        foreach ($implements as $implement) {
            if (!is_array($implement)) {
                continue;
            }

            $name = trim((string) ($implement['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $areaNames = $implement['areas_use'] ?? [];
            if (!is_array($areaNames)) {
                $areaNames = [];
            }

            $areaIds = [];
            foreach ($areaNames as $areaName) {
                if (!is_string($areaName)) {
                    continue;
                }

                $areaId = $areasByName[$areaName] ?? null;
                if ($areaId) {
                    $areaIds[] = (int) $areaId;
                }
            }

            $areaIds = array_values(array_unique($areaIds));

            CleaningImplement::updateOrCreate(
                [
                    'team_id' => $team->id,
                    'name' => $name,
                ],
                [
                    'description' => $implement['description'] ?? null,
                    'type' => $implement['type'] ?? 'reutilizable',
                    'areas_use' => $areaIds,
                    'active' => $implement['active'] ?? true,
                ]
            );
        }
    }

    public function populateDesinfectants(Team $team): void
    {
        $desinfectants = config('desinfectants', []);
        if (!is_array($desinfectants)) {
            return;
        }

        $areasByName = StablishmentArea::query()
            ->where('team_id', $team->id)
            ->pluck('id', 'name');

        foreach ($desinfectants as $desinfectant) {
            if (!is_array($desinfectant)) {
                continue;
            }

            $name = trim((string) ($desinfectant['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $concentration = trim((string) ($desinfectant['concentration'] ?? ''));
            if ($concentration === '') {
                continue;
            }

            $areaNames = $desinfectant['applicable_areas'] ?? [];
            if (!is_array($areaNames)) {
                $areaNames = [];
            }

            $areaIds = [];
            foreach ($areaNames as $areaName) {
                if (!is_string($areaName)) {
                    continue;
                }

                $areaId = $areasByName[$areaName] ?? null;
                if ($areaId) {
                    $areaIds[] = (int) $areaId;
                }
            }

            $areaIds = array_values(array_unique($areaIds));

            Desinfectant::updateOrCreate(
                [
                    'team_id' => $team->id,
                    'name' => $name,
                    'concentration' => $concentration,
                ],
                [
                    'active_ingredient' => $desinfectant['active_ingredient'] ?? null,
                    'indications' => $desinfectant['indications'] ?? null,
                    'level' => $desinfectant['level'] ?? 'medio',
                    'applicable_areas' => $areaIds,
                    'active' => $desinfectant['active'] ?? true,
                ]
            );
        }
    }

    private function isMinutesIvcLeafEntry(array $item): bool
    {
        return array_key_exists('entry_id', $item) && array_key_exists('question', $item);
    }

    public function createPermissionsAndSyncRole(Team $team, Role $roleAdmin): void
    {
        $permissionNames = [];
        foreach (PermissionType::cases() as $permissionType) {
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

    public function createTenantSettingsFromConfig(Team $team): void
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

        $defaultProcessMap = [['path' => 'public_process_map.png']];
        $defaultOrgChart = [['path' => 'public_organization_chart.png']];

        $settingsByKey = Setting::pluck('id', 'key');

        foreach ($settingsByKey as $settingKey => $settingId) {
            $existingTenantSetting = TenantSetting::where('team_id', $team->id)
                ->where('setting_id', $settingId)
                ->first();

            switch ($settingKey) {
                case 'Misión':
                    $value = $mission;
                    $data = null;
                    break;
                case 'Visión':
                    $value = $vision;
                    $data = null;
                    break;
                case 'Política de Calidad':
                    $value = $policyText;
                    $data = $policyData;
                    break;
                case 'Mapa de Procesos':
                    if ($existingTenantSetting && ! $this->isEmptyFileSettingValue($existingTenantSetting->value)) {
                        continue;
                    }
                    $value = $defaultProcessMap;
                    $data = null;
                    break;
                case 'Organigrama':
                    if ($existingTenantSetting && ! $this->isEmptyFileSettingValue($existingTenantSetting->value)) {
                        continue;
                    }
                    $value = $defaultOrgChart;
                    $data = null;
                    break;
                default:
                    $value = null;
                    $data = null;
            }
            TenantSetting::updateOrCreate(
                ['team_id' => $team->id, 'setting_id' => $settingId],
                ['value' => $value, 'data'  => $data, 'updated_at' => now()]
            );
        }
    }

    public function populateValuesSetting(Team $team): void
    {
        // Config con los valores tipo ["Transparencia" => "Llevamos...", ...]
        $values = config('ethical_values', []);
        if (!is_array($values) || empty($values)) {
            return;
        }

        // Setting global para "Valores" en grupo "Plataforma Estratégica"
        $setting = Setting::where('key', 'Valores')
            ->where('group', 'Plataforma Estratégica')
            ->first();

        if (!$setting) {
            Log::warning('Setting "Valores" no encontrado para Plataforma Estratégica');
            return;
        }

        TenantSetting::updateOrCreate(
            [
                'team_id'    => $team->id,
                'setting_id' => $setting->id,
            ],
            [
                // se guarda como JSON y se castea a array en el modelo
                'value' => $values,
                'data'  => null,
            ]
        );
    }

    private function isEmptyFileSettingValue($value): bool
    {
        if (is_null($value) || $value === '') {
            return true;
        }

        if (is_array($value)) {
            return empty($value);
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return empty($decoded);
            }
        }

        return false;
    }


    /* public function populateDocumentsFromConfig(Team $team, ?User $consultant): void
    {
        $templates = config('document_templates.default_docs', []);
        if (!is_array($templates) || empty($templates)) return;

        foreach ($templates as $tpl) {
            $processId = Process::where('code', $tpl['process_id'] ?? null)->value('id');
            $categoryId = DocumentCategory::where('code', $tpl['document_category_id'] ?? null)->value('id');

            if (!$processId || !$categoryId) {
                Log::warning("Plantilla de documento: tipo o proceso no hallado", $tpl);
                continue;
            }

            Document::updateOrCreate(
                ['team_id' => $team->id, 'slug' => $tpl['slug']],
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
    } */


    public function populateDocumentsFromConfig(Team $team, ?User $consultant): void
    {
        $templates = $this->loadDocumentTemplates();
        if (!is_array($templates) || empty($templates)) {
            Log::warning('No hay plantillas en config(document_templates.default_docs).');
            return;
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $templateSlugs = [];
        $skipReasons = [];

        foreach ($templates as $tpl) {
            if (!is_array($tpl)) {
                $skipped++;
                $skipReasons[] = 'tpl no array';
                continue;
            }

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

            if (!$processId || !$categoryId) {
                Log::warning('Plantilla omitida: proceso o category no encontrados', [
                    'slug' => $slug,
                    'process_code' => $tpl['process_id'] ?? null,
                    'category_code' => $tpl['document_category_id'] ?? null,
                ]);
                $skipped++;
                $skipReasons[] = "{$slug}: process/category no resuelto";
                continue;
            }

            $document = Document::withTrashed()->firstOrNew(
                [
                    'team_id' => $team->id,
                    'slug' => $slug,
                ]
            );

            if ($document->exists && method_exists($document, 'trashed') && $document->trashed()) {
                $document->restore();
            }

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

            $templateData = Arr::wrap($tpl['data'] ?? []);
            if (! array_key_exists('submitted_for_review_at', $templateData)) {
                $templateData['submitted_for_review_at'] = now()->toDateTimeString();
            }

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
                'data' => $templateData,
                'prepared_by' => $consultant?->id,
                'reviewed_by' => is_numeric($tpl['reviewed_by'] ?? null) ? (int) $tpl['reviewed_by'] : null,
                'approved_by' => is_numeric($tpl['approved_by'] ?? null) ? (int) $tpl['approved_by'] : null,
            ]);

            $document->save();

            if ($wasExisting) {
                $updated++;
            } else {
                $created++;
            }
        }

        Log::info('Plantillas de documentos procesadas', [
            'team_id' => $team->id,
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
            'slugs' => array_values(array_unique($templateSlugs)),
            'skip_reasons' => array_values(array_unique($skipReasons)),
        ]);
    }

    private function loadDocumentTemplates(): array
    {
        $fromConfig = config('document_templates.default_docs', []);
        $fromConfig = is_array($fromConfig) ? $fromConfig : [];

        $fromFile = [];
        $path = config_path('document_templates.php');
        if (is_file($path) && is_readable($path)) {
            $fromFile = $this->loadDocumentTemplatesFromDisk($path);

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

        if (!empty($fromFile)) {
            return $fromFile;
        }

        return $fromConfig;
    }

    private function loadDocumentTemplatesFromDisk(string $path): array
    {
        try {
            $contents = file_get_contents($path);
            if (!is_string($contents) || trim($contents) === '') {
                return [];
            }

            // Eliminar BOM (Byte Order Mark) si está presente al inicio del archivo.
            if (str_starts_with($contents, "\xEF\xBB\xBF")) {
                $contents = substr($contents, 3);
            }

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
            if (!is_array($tpl)) {
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
            ->where('team_id', $team->id)
            ->first();

        if ($process) {
            return $process->id;
        }

        $defaults = config('processes_templates.default_processes', []);
        if (!is_array($defaults)) {
            return null;
        }

        $default = collect($defaults)->first(function ($item) use ($code) {
            return is_array($item) && (($item['code'] ?? null) === $code);
        });

        if (!is_array($default)) {
            return null;
        }

        $processTypeId = $this->resolveTemplateProcessTypeId($default, $code);
        if (!$processTypeId) {
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
        if (!is_array($defaults)) {
            return null;
        }

        $default = collect($defaults)->first(function ($item) use ($typeCode) {
            return is_array($item)
                && strtoupper((string) ($item['code'] ?? '')) === $typeCode;
        });

        if (!is_array($default)) {
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
        if (!is_array($defaults) || empty($defaults)) {
            $defaults = config('document_categories.document_categories', []);
        }

        if (!is_array($defaults)) {
            return null;
        }

        $default = collect($defaults)->first(function ($item) use ($code) {
            return is_array($item) && (($item['code'] ?? null) === $code);
        });

        if (!is_array($default)) {
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
    /**
     * Procesos
     */
    public function populateProcessesTemplatesFromConfig(Team $team): void
    {
        $templates = $this->loadProcessesTemplates();
        if (!is_array($templates) || empty($templates)) {
            Log::warning('No hay plantillas en config(process_templates.default_processes).');
            return;
        }
        
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $skipReasons = [];

        foreach ($templates as $tpl) {
            if (!is_array($tpl)) {
                $skipped++;
                $skipReasons[] = 'tpl no array';
                continue;
            }

            $document = Process::withTrashed()->firstOrNew(
                [
                    'team_id' => $team->id,
                    'code' => $tpl['code'] ?? null,
                ]
            );

            if ($document->exists && method_exists($document, 'trashed') && $document->trashed()) {
                $document->restore();
            }

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

            $document->fill([
                'process_type_id' => $tpl['process_type_id'] ?? null,
                'records' => Arr::wrap($tpl['records'] ?? []),
                'code' => $tpl['code'] ?? null,
                'name' => $tpl['name'] ?? null,
                'slug' => $tpl['slug'] ?? null,
                'description' => $tpl['description'] ?? null, //json
                'suppliers' => Arr::wrap($tpl['suppliers'] ?? []),
                'inputs' => Arr::wrap($tpl['inputs'] ?? []),
                'procedures' => Arr::wrap($tpl['procedures'] ?? []),
                'outputs' => Arr::wrap($tpl['outputs'] ?? []),
                'clients' => Arr::wrap($tpl['clients'] ?? []),
                'data' => Arr::wrap($tpl['data'] ?? []),
            ]);

            $document->save();

            if ($wasExisting) {
                $updated++;
            } else {
                $created++;
            }
        }
    }

    private function loadProcessesTemplates(): array
    {
        $fromConfig = config('processes_templates.default_processes', []);
        $fromConfig = is_array($fromConfig) ? $fromConfig : [];

        $fromFile = [];
        $path = config_path('processes_templates.php');
        if (is_file($path) && is_readable($path)) {
            $fromFile = $this->loadProcessesTemplatesFromDisk($path);

            if (empty($fromFile)) {
                if (function_exists('opcache_invalidate')) {
                    @opcache_invalidate($path, true);
                }
                clearstatcache(true, $path);

                $raw = include $path;
                if (is_array($raw) && is_array($raw['default_processes'] ?? null)) {
                    $fromFile = $raw['default_processes'];
                }
            }
        }

        Log::info('Carga de document_templates', [
            'from_config_count' => count($fromConfig),
            'from_file_count' => count($fromFile),
            'path' => $path,
        ]);

        if (!empty($fromFile)) {
            return $fromFile;
        }

        return $fromConfig;
    }

    private function loadProcessesTemplatesFromDisk(string $path): array
    {
        try {
            $contents = file_get_contents($path);
            if (!is_string($contents) || trim($contents) === '') {
                return [];
            }

            // Eliminar BOM (Byte Order Mark) si está presente al inicio del archivo.
            if (str_starts_with($contents, "\xEF\xBB\xBF")) {
                $contents = substr($contents, 3);
            }

            $raw = (static function (string $phpCode) {
                return eval('?>' . $phpCode);
            })($contents);

            if (is_array($raw) && is_array($raw['default_processes'] ?? null)) {
                return $raw['default_processes'];
            }
        } catch (\Throwable $e) {
            Log::warning('No se pudo leer processes_templates.php desde disco con eval', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
        }

        return [];
    }

    /**
     * Fin populate procesos
     */


    public function populateSchedules(Team $team, ?User $owner = null, ?Role $roleAdmin = null): void
    {
        $this->populateTrainingSchedule($team, $owner, $roleAdmin);
        $this->populateInternalAuditSchedule($team, $owner, $roleAdmin);
        $this->populateCleaningSchedule($team, $owner, $roleAdmin);
        $this->populateEquipmentCalibrationSchedule($team, $owner, $roleAdmin);
    }

    public function populateScheduleByKey(Team $team, string $configKey, ?User $owner = null, ?Role $roleAdmin = null): void
    {
        switch ($configKey) {
            case 'training_schedule':
                $this->populateTrainingSchedule($team, $owner, $roleAdmin);
                break;
            case 'cleaning_schedule':
                $this->populateCleaningSchedule($team, $owner, $roleAdmin);
                break;
            case 'equipment_calibration_schedule':
                $this->populateEquipmentCalibrationSchedule($team, $owner, $roleAdmin);
                break;
            case 'internal_audit_schedule':
                $this->populateInternalAuditSchedule($team, $owner, $roleAdmin);
                break;
            default:
                Log::warning("Config de cronograma no reconocida: {$configKey}");
        }
    }

    public function populateInternalAuditSchedule(Team $team, ?User $owner = null, ?Role $roleAdmin = null): void
    {
        $payload = $this->resolveSchedulePayload('internal_audit_schedule', [
            'sections' => config('minutes-ivc-sections', []),
        ]);

        if ($payload === null) {
            return;
        }

        $this->persistSchedulePayload($team, $owner, $roleAdmin, $payload, 'internal_audit_schedule');
    }

    public function populateCleaningSchedule(Team $team, ?User $owner = null, ?Role $roleAdmin = null): void
    {
        $payload = $this->resolveSchedulePayload('cleaning_schedule');

        if ($payload === null) {
            return;
        }

        $this->persistSchedulePayload($team, $owner, $roleAdmin, $payload, 'cleaning_schedule');
    }

    public function populateEquipmentCalibrationSchedule(Team $team, ?User $owner = null, ?Role $roleAdmin = null): void
    {
        $payload = $this->resolveSchedulePayload('equipment_calibration_schedule');

        if ($payload === null) {
            return;
        }

        $this->persistSchedulePayload($team, $owner, $roleAdmin, $payload, 'equipment_calibration_schedule');
    }

    public function populateTrainingSchedule(Team $team, ?User $owner = null, ?Role $roleAdmin = null): void
    {
        $payload = $this->resolveSchedulePayload('training_schedule');

        if ($payload === null) {
            return;
        }

        $this->persistSchedulePayload($team, $owner, $roleAdmin, $payload, 'training_schedule');
    }

    private function resolveSchedulePayload(string $configKey, array $context = []): ?array
    {
        $builder = config($configKey);

        if (! is_callable($builder)) {
            Log::warning("{$configKey} config no es callable");
            return null;
        }

        $payload = $builder(now(), $context);
        if (! is_array($payload)) {
            Log::warning("{$configKey} config debe retornar array");
            return null;
        }

        // Compatibilidad con training_schedule legado (lista de sesiones).
        if (
            $configKey === 'training_schedule'
            && ! array_key_exists('schedule', $payload)
            && ! array_key_exists('events', $payload)
        ) {
            $payload = $this->buildTrainingPayloadFromLegacyItems($payload);
        }

        $schedule = $payload['schedule'] ?? null;
        $events = $payload['events'] ?? null;

        if (! is_array($schedule) || ! is_array($events)) {
            Log::warning("{$configKey} config debe retornar ['schedule' => [], 'events' => []]");
            return null;
        }

        return [
            'schedule' => $schedule,
            'events' => $events,
        ];
    }

    private function buildTrainingPayloadFromLegacyItems(array $items): array
    {
        $events = [];
        $minStart = null;
        $maxEnd = null;
        $scheduleColor = '#4CAF50';
        $scheduleIcon = 'phosphor-graduation-cap';

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $title = trim((string) ($item['name'] ?? ''));
            $startAt = $this->parseDateTime($item['starts_at'] ?? null);
            $endAt = $this->parseDateTime($item['ends_at'] ?? null);

            if ($title === '' || $startAt === null || $endAt === null) {
                continue;
            }

            $scheduleColor = $this->normalizeColor($item['color'] ?? $scheduleColor);
            $scheduleIcon = is_string($item['icon'] ?? null) && trim((string) $item['icon']) !== ''
                ? trim((string) $item['icon'])
                : $scheduleIcon;

            $minStart = $minStart === null || $startAt->lt($minStart) ? $startAt->copy() : $minStart;
            $maxEnd = $maxEnd === null || $endAt->gt($maxEnd) ? $endAt->copy() : $maxEnd;

            $descriptionParts = [];
            if (is_string($item['description'] ?? null) && trim((string) $item['description']) !== '') {
                $descriptionParts[] = trim((string) $item['description']);
            }
            if (is_string($item['objective'] ?? null) && trim((string) $item['objective']) !== '') {
                $descriptionParts[] = 'Objetivo: ' . trim((string) $item['objective']);
            }

            $events[] = [
                'title' => $title,
                'description' => implode(' ', $descriptionParts),
                'type' => 'task',
                'start_date' => $startAt->toDateString(),
                'end_date' => $endAt->toDateString(),
                'has_time' => true,
                'start_time' => $startAt->format('H:i:s'),
                'end_time' => $endAt->format('H:i:s'),
            ];
        }

        return [
            'schedule' => [
                'name' => 'Cronograma de capacitaciones',
                'slug' => 'cronograma-de-capacitaciones',
                'description' => 'Cronograma de capacitaciones sobre procesos del servicio farmacéutico; procesos estratégicos, misionales, de apoyo y mejora continua',
                'objective' => 'Fortalecer competencias del equipo mediante sesiones programadas.',
                'starts_at' => $minStart?->toDateTimeString(),
                'ends_at' => $maxEnd?->toDateTimeString(),
                'color' => $scheduleColor,
                'icon' => $scheduleIcon,
            ],
            'events' => $events,
        ];
    }

    private function persistSchedulePayload(
        Team $team,
        ?User $owner,
        ?Role $roleAdmin,
        array $payload,
        string $configKey
    ): void {
        $scheduleData = is_array($payload['schedule'] ?? null) ? $payload['schedule'] : [];
        $eventsData = is_array($payload['events'] ?? null) ? $payload['events'] : [];

        $name = trim((string) ($scheduleData['name'] ?? ''));
        if ($name === '') {
            Log::warning("{$configKey}: schedule.name vacio, no se puede poblar");
            return;
        }

        $userId = $owner?->id;
        if (! $userId) {
            $userId = $team->users()
                ->orderBy('users.id')
                ->value('users.id');
        }
        if (! $userId) {
            $userId = auth()->id();
        }
        if (! $userId) {
            Log::warning("{$configKey}: user_id requerido para crear schedule/eventos");
            return;
        }

        [$derivedStart, $derivedEnd] = $this->deriveScheduleRangeFromEvents($eventsData);
        $startsAt = $this->parseDateTime($scheduleData['starts_at'] ?? null) ?? $derivedStart;
        $endsAt = $this->parseDateTime($scheduleData['ends_at'] ?? null) ?? $derivedEnd;

        $schedule = Schedule::updateOrCreate(
            [
                'team_id' => $team->id,
                'name' => $name,
            ],
            [
                'user_id' => $userId,
                'description' => $scheduleData['description'] ?? null,
                'objective' => $scheduleData['objective'] ?? null,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'color' => $this->normalizeColor($scheduleData['color'] ?? null),
                'icon' => is_string($scheduleData['icon'] ?? null) ? trim((string) $scheduleData['icon']) : null,
            ]
        );

        $keptEventIds = [];

        foreach ($eventsData as $eventData) {
            if (! is_array($eventData)) {
                continue;
            }

            $title = trim((string) ($eventData['title'] ?? ''));
            if ($title === '') {
                continue;
            }

            $startDate = $this->parseDate($eventData['start_date'] ?? null);
            if ($startDate === null) {
                Log::warning("{$configKey}: evento omitido por start_date invalido", [
                    'title' => $title,
                    'raw_start_date' => $eventData['start_date'] ?? null,
                ]);
                continue;
            }

            $endDate = $this->parseDate($eventData['end_date'] ?? null) ?? $startDate;
            $type = in_array(($eventData['type'] ?? 'task'), ['event', 'task', 'milestone'], true)
                ? (string) $eventData['type']
                : 'task';

            $hasTime = filter_var($eventData['has_time'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $startTime = $hasTime ? $this->parseTime($eventData['start_time'] ?? null) : null;
            $endTime = $hasTime ? $this->parseTime($eventData['end_time'] ?? null) : null;

            $event = Event::updateOrCreate(
                [
                    'team_id' => $team->id,
                    'schedule_id' => $schedule->id,
                    'title' => $title,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ],
                [
                    'user_id' => $userId,
                    'role_id' => $roleAdmin?->id,
                    'description' => $eventData['description'] ?? ($scheduleData['description'] ?? null),
                    'type' => $type,
                    'has_time' => $hasTime,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ]
            );

            $keptEventIds[] = $event->id;
        }

        if (empty($keptEventIds)) {
            $schedule->events()->where('team_id', $team->id)->delete();
            return;
        }

        $schedule->events()
            ->where('team_id', $team->id)
            ->whereNotIn('id', $keptEventIds)
            ->delete();
    }

    private function deriveScheduleRangeFromEvents(array $eventsData): array
    {
        $minDate = null;
        $maxDate = null;

        foreach ($eventsData as $eventData) {
            if (! is_array($eventData)) {
                continue;
            }

            $startDate = $this->parseDate($eventData['start_date'] ?? null);
            $endDate = $this->parseDate($eventData['end_date'] ?? null) ?? $startDate;

            if ($startDate === null || $endDate === null) {
                continue;
            }

            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();

            $minDate = $minDate === null || $start->lt($minDate) ? $start : $minDate;
            $maxDate = $maxDate === null || $end->gt($maxDate) ? $end : $maxDate;
        }

        return [$minDate, $maxDate];
    }

    private function parseDate(mixed $value): ?string
    {
        if ($value instanceof DateTimeInterface) {
            return Carbon::instance($value)->toDateString();
        }

        if (is_string($value) && trim($value) !== '') {
            try {
                return Carbon::parse($value)->toDateString();
            } catch (\Throwable) {
                return null;
            }
        }

        return null;
    }

    private function parseDateTime(mixed $value): ?Carbon
    {
        if ($value instanceof DateTimeInterface) {
            return Carbon::instance($value);
        }

        if (is_string($value) && trim($value) !== '') {
            try {
                return Carbon::parse($value);
            } catch (\Throwable) {
                return null;
            }
        }

        return null;
    }

    private function parseTime(mixed $value): ?string
    {
        if ($value instanceof DateTimeInterface) {
            return Carbon::instance($value)->format('H:i:s');
        }

        if (is_string($value) && trim($value) !== '') {
            try {
                return Carbon::parse($value)->format('H:i:s');
            } catch (\Throwable) {
                return null;
            }
        }

        return null;
    }

    private function normalizeColor(mixed $color): string
    {
        if (is_string($color) && preg_match('/^#[0-9A-Fa-f]{6}$/', $color) === 1) {
            return strtoupper($color);
        }

        return '#0F766E';
    }

    public function enrollInitialCourse(Team $team, User $owner): void
    {
        $courseTitle = '¿Cómo pasar la visita de la Secretaría de Salud?';
        $course = Course::where('title', $courseTitle)->first();

        if (!$course) {
            Log::warning("Curso inicial no encontrado: {$courseTitle}");
            return;
        }

        Enrollment::firstOrCreate([
            'team_id' => $team->id,
            'user_id' => $owner->id,
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
