<?php

namespace App\Services;

use App\Enums\PermissionType;
use App\Models\Quality\Training\Course;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Quality\Training\Enrollment;
use App\Models\Event;
use App\Models\ManagementIndicator;
use App\Models\MinutesIvcSection;
use App\Models\MinutesIvcSectionEntry;
use App\Models\Process;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\Team;
use App\Models\TenantSetting;
use App\Models\User;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class TeamSetupServiceOld
{
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
        $indicatorNames = config('management-indicators', []);
        $indicators = ManagementIndicator::whereIn('name', $indicatorNames)->get()->keyBy('name');

        foreach ($indicatorNames as $name) {
            $indicator = $indicators->get($name);
            if (!$indicator) {
                Log::warning("ManagementIndicator no hallado para name='{$name}'");
                continue;
            }

            $team->managementIndicators()->syncWithoutDetaching([
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

    public function populateIvcSectionsAndEntries(Team $team): void
    {
        $sections = config('minutes-ivc-sections', []);
        if (!is_array($sections)) return;

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

        $teamSections = MinutesIvcSection::where('team_id', $team->id)
            ->get()
            ->keyBy(fn(MinutesIvcSection $section): string => (string) $section->order);

        foreach ($sectionConfigByOrder as $order => $configKey) {
            $section = $teamSections->get((string) $order);
            if (!$section) {
                Log::warning("Sección IVC no encontrada para order={$order} (team {$team->id})");
                continue;
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

        $settingsByKey = Setting::pluck('id', 'key');

        foreach ($settingsByKey as $settingKey => $settingId) {
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
        $templates = config('document_templates.default_docs', []);
        if (!is_array($templates) || empty($templates)) {
            return;
        }

        // Mapear códigos -> ids para evitar N+1
        $processMap  = Process::pluck('id', 'code');
        $categoryMap = DocumentCategory::pluck('id', 'code');

        foreach ($templates as $tpl) {
            $processCode  = $tpl['process_id'] ?? null;              // idealmente process_code
            $categoryCode = $tpl['document_category_id'] ?? null;    // idealmente document_category_code

            $processId  = $processCode  ? ($processMap[$processCode]  ?? null) : null;
            $categoryId = $categoryCode ? ($categoryMap[$categoryCode] ?? null) : null;

            if (!$processId || !$categoryId) {
                Log::warning('Plantilla de documento: tipo o proceso no hallado', $tpl);
                continue;
            }

            Document::updateOrCreate(
                [
                    'team_id' => $team->id,
                    'slug'    => $tpl['slug'],
                ],
                [
                    'title'                => $tpl['title'] ?? null,
                    'sequence'             => 0, // o calcula siguiente secuencia
                    'process_id'           => $processId,
                    'document_category_id' => $categoryId,
                    'objective'            => $tpl['objective'] ?? null,
                    'scope'                => $tpl['scope'] ?? null,
                    'references'           => $tpl['references'] ?? [],
                    'terms'                => $tpl['terms'] ?? [],
                    'responsibilities'     => $tpl['responsibilities'] ?? [],
                    'procedure'            => $tpl['procedure'] ?? [],
                    'records'              => $tpl['records'] ?? [],
                    'annexes'              => $tpl['annexes'] ?? [],
                    'data'                 => $tpl['data'] ?? [],
                    'prepared_by'          => $consultant?->id,
                    // mejor dejar estos en null o resolverlos por otro mecanismo
                    'reviewed_by'          => null,
                    'approved_by'          => null,
                ]
            );
        }
    }


    public function populateSchedules(Team $team, User $owner, Role $roleAdmin): void
    {
        $this->populateTrainingSchedule($team, $owner, $roleAdmin);
        $this->populateInternalAuditSchedule($team, $owner, $roleAdmin);
        $this->populateCleaningSchedule($team, $owner, $roleAdmin);
        $this->populateEquipmentCalibrationSchedule($team, $owner, $roleAdmin);
    }

    public function populateInternalAuditSchedule(Team $team, User $owner, Role $roleAdmin): void
    {
        $payload = $this->resolveSchedulePayload('internal_audit_schedule', [
            'sections' => config('minutes-ivc-sections', []),
        ]);

        if ($payload === null) {
            return;
        }

        $this->persistSchedulePayload($team, $owner, $roleAdmin, $payload, 'internal_audit_schedule');
    }

    public function populateCleaningSchedule(Team $team, User $owner, Role $roleAdmin): void
    {
        $payload = $this->resolveSchedulePayload('cleaning_schedule');

        if ($payload === null) {
            return;
        }

        $this->persistSchedulePayload($team, $owner, $roleAdmin, $payload, 'cleaning_schedule');
    }

    public function populateEquipmentCalibrationSchedule(Team $team, User $owner, Role $roleAdmin): void
    {
        $payload = $this->resolveSchedulePayload('equipment_calibration_schedule');

        if ($payload === null) {
            return;
        }

        $this->persistSchedulePayload($team, $owner, $roleAdmin, $payload, 'equipment_calibration_schedule');
    }

    public function populateTrainingSchedule(Team $team, User $owner, Role $roleAdmin): void
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
                'name' => 'Cronograma de capacitacion',
                'description' => 'Cronograma de capacitaciones generado desde config.training_schedule.',
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
        User $owner,
        Role $roleAdmin,
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

        [$derivedStart, $derivedEnd] = $this->deriveScheduleRangeFromEvents($eventsData);
        $startsAt = $this->parseDateTime($scheduleData['starts_at'] ?? null) ?? $derivedStart;
        $endsAt = $this->parseDateTime($scheduleData['ends_at'] ?? null) ?? $derivedEnd;

        $schedule = Schedule::updateOrCreate(
            [
                'team_id' => $team->id,
                'name' => $name,
            ],
            [
                'user_id' => $owner->id,
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
                    'user_id' => $owner->id,
                    'role_id' => $roleAdmin->id,
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
