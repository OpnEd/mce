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
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class TeamSetupService
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
        $this->populateTrainingSchedule($team, $owner, $roleAdmin);
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
        ): void
    {
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
            ->keyBy(fn (MinutesIvcSection $section): string => (string) $section->order);

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

        $allSettingIds = Setting::pluck('id');

        foreach ($allSettingIds as $settingId) {
            switch ($settingId) {
                case 1: $value = $mission; $data = null; break;
                case 2: $value = $vision; $data = null; break;
                case 3: $value = $policyText; $data = $policyData; break;
                default: $value = null; $data = null;
            }
            TenantSetting::updateOrCreate(
                ['team_id' => $team->id, 'setting_id' => $settingId],
                ['value' => $value, 'data'  => $data, 'updated_at' => now()]
            );
        }
    }

    public function populateDocumentsFromConfig(Team $team, ?User $consultant): void
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
    }

    public function populateTrainingSchedule(Team $team, User $owner, Role $roleAdmin): void
    {
        $callable = config('training_schedule');
        if (!is_callable($callable)) {
            Log::warning("training_schedule config no es callable");
            return;
        }

        $items = $callable(now());
        foreach ($items as $item) {
            $schedule = Schedule::create(array_merge($item, [
                'team_id' => $team->id,
                'user_id' => $owner->id,
            ]));

            Event::create([
                'team_id' => $team->id,
                'user_id' => $owner->id,
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
