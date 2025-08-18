<?php

namespace App\Filament\Pages\Tenancy;

use App\Enums\PermissionType;
use App\Models\Event;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\ManagementIndicator;
use App\Models\MinutesIvcSection;
use App\Models\Permission;
use App\Models\Process;
use App\Models\ProcessType;
use App\Models\Quality\Training\Course;
use App\Models\Quality\Training\Enrollment;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Team;
use App\Models\TenantSetting;
use App\Models\User;
use Database\Seeders\ManagementIndicatorTeamSeeder;
use Dom\Text;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\PermissionRegistrar;

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
                TextInput::make('name')
                    ->label('Nombre de la compañía')
                    ->required(),
                TextInput::make('identification')
                    ->label('NIT')
                    ->required(),
                TextInput::make('address')
                    ->label('Dirección')
                    ->required(),
                TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->required(),
                TextInput::make('phonenumber')
                    ->label('Teléfono (fijo o celular)')
                    ->tel()
                    ->required(),
            ]);
    }

    protected function handleRegistration(array $data): Team
    {
        $team = Team::create($data);
        $consultant = User::where('id', 1)->first();

        $team->users()->attach(\Illuminate\Support\Facades\Auth::user());
        $team->users()->attach($consultant);

        $roleAdmin = Role::firstOrCreate(
            [
                'name' => 'Administrador',
                'guard_name' => 'web',
                'team_id' => $team->id,
            ]
        );
        $roleConsultant = Role::firstOrCreate(
            [
                'name' => 'Consultor',
                'guard_name' => 'web',
                'team_id' => $team->id,
            ]
        );

        // 4) ¡Muy importante! Configura el team activo para Spatie:
        app(PermissionRegistrar::class)
            ->setPermissionsTeamId($team->id);

        // 5) Asigna el rol **sobre el modelo User**, no sobre su ID
        $user = Auth::user();
        $user->assignRole($roleAdmin);

        $consultant->assignRole($roleConsultant);

        // Definimos los nombres de los indicadores según tu array original

        $indicators = config('management-indicators');

        $sections = config('minutes-ivc-sections');

        $entries = config('minutes-ivc-nine-section-entries');

        $permissionNames = [];

        foreach ($indicators as $name) {
            $indicator = ManagementIndicator::where('name', $name)->first();

            if (! $indicator) {
                // Opcional: lanza excepción, loguéalo o haz continue
                \Illuminate\Support\Facades\Log::warning("ManagementIndicator no hallado para name='{$name}'");
                continue;
            }
            // Conectar sin eliminar posibles existentes
            $team->managementIndicators()
                ->syncWithoutDetaching([
                    $indicator->id => [
                        'role_id' => $roleAdmin->id,
                        // Periodicidad arbitraria; ajústala si necesitas distintos valores
                        'periodicity'    => 'Mensual',
                        // Usamos la meta global como meta personalizada
                        'indicator_goal' => $indicator->indicator_goal,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ],
                ]);
        }


        foreach ($sections as $s) {
            \App\Models\MinutesIvcSection::updateOrCreate(
                //
                [
                    'team_id' => $team->id,
                    'order' => $s['order'],
                    'slug' => $s['slug'],
                    'name' => $s['name'],
                    'description' => $s['description'],
                    'status' => $s['status']
                ]
            );
        }

        $minutes_ivc_section = MinutesIvcSection::where('team_id', $team->id)->where('name', 'Sistema de gestión de calidad')->first();
        $minutes_ivc_section_id = $minutes_ivc_section->id;

        foreach ($entries as $e) {
            \App\Models\MinutesIvcSectionEntry::updateOrCreate(
                //
                [
                    'minutes_ivc_section_id' => $minutes_ivc_section_id,
                    'apply' => $e['apply'],
                    'entry_id' => $e['entry_id'],
                    'criticality' => $e['criticality'],
                    'question' => $e['question'],
                    'answer' => $e['answer'],
                    'entry_type' => $e['entry_type'],
                    'links' => $e['links'],
                    'compliance' => $e['compliance'],
                ]
            );
        }

        foreach (PermissionType::cases() as $permissionType) {
            $perm = Permission::firstOrCreate([
                'name'       => $permissionType->value,
                'guard_name' => 'web',
                'team_id'    => $team->id, // si aplica
            ]);

            // Si tu tabla permissions tiene columna 'label', la actualizamos:
            if (array_key_exists('label', $perm->getAttributes())) {
                $perm->label = $permissionType->getLabel();
                $perm->save();
            }

            $permissionNames[] = $perm->name;
        }

        // 4) Fijamos el context de team para la asignación, si usas teams
        // app(PermissionRegistrar::class)->setPermissionsTeamId($tuTeamId);

        // 5) Sincronizamos todos los permisos al rol
        $roleAdmin->syncPermissions($permissionNames);

        // 1) Carga desde config los textos de misión, visión y política
        $cfg = config('tenant_settings');

        $missionText = $cfg['mission'] ?? null;
        $visionText  = $cfg['vision']  ?? null;
        $policy      = $cfg['quality_policy'] ?? [];

        $policyText  = $policy['statement']   ?? null;
        $policyData  = [
            'objectives'  => $policy['objectives']  ?? [],
            'commitments' => $policy['commitments'] ?? [],
        ];

        // 2) Recorre todos los Setting existentes
        $allSettingIds = Setting::pluck('id');

        foreach ($allSettingIds as $settingId) {
            // Decide el value y el data según el ID
            switch ($settingId) {
                case 1: // Misión
                    $value = $missionText;
                    $data  = null;
                    break;
                case 2: // Visión
                    $value = $visionText;
                    $data  = null;
                    break;
                case 3: // Política de Calidad
                    $value = $policyText;
                    $data  = $policyData;
                    break;
                default:
                    $value = null;
                    $data  = null;
            }

            // 3) Inserta o actualiza el TenantSetting
            TenantSetting::updateOrCreate(
                [
                    'team_id'    => $team->id,
                    'setting_id' => $settingId,
                ],
                [
                    'value'      => $value,
                    'data'       => $data,
                    'updated_at' => now(),
                ]
            );
        }

        //  POBLAMIENTO AUTOMÁTICO DE DOCUMENTOS 
        //

        // 1) Carga las plantillas
        $templates = config('document_templates.default_docs', []);

        foreach ($templates as $tpl) {

            $consultant = User::where('id', 1)
                ->first();
            //dd($consultant);
            // 2) Resuelve ProcessType y DocumentType por su código
            $processId  = Process::where('code', $tpl['process_id'])
                ->value('id');
            $categoryId = DocumentCategory::where('code', $tpl['document_category_id'])
                ->value('id');

            if (! $processId || ! $categoryId) {
                // Opcional: loguea o lanza excepción si no los encuentra
                Log::warning("Plantilla de documento: tipo o proceso no hallado", $tpl);
                continue;
            }

            // 3) Inserta o actualiza el documento para este team
            Document::updateOrCreate(
                [
                    'team_id' => $team->id,
                    'slug'    => $tpl['slug'],
                ],
                [
                    'title'                => $tpl['title'],
                    'sequence'             => 0, // o calcula si usas sequence automático
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
                    'prepared_by'          => $consultant->id,
                    'reviewed_by'          => $tpl['reviewed_by'] ?: null,
                    'approved_by'          => $tpl['approved_by'] ?: null,
                    'updated_at'           => now(),
                ]
            );
        }

        $scheduleItems = config('training_schedule')(now());

        foreach ($scheduleItems as $item) {
            $schedule = \App\Models\Schedule::create(array_merge($item, [
                'team_id' => $team->id,
                'user_id' => Auth::id(),
            ]));

            // Clonar a la tabla Events para que se visualice en el calendario
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
        //  fin poblamiento de documentos
        Enrollment::create([
            'team_id' => $team->id,
            'user_id' => Auth::id(),
            'course_id' => Course::where('title', '¿Cómo pasar la visita de la Secretaría de Salud?')->first()->id,
            'status' => 'in_progress', // e.g. => , 'completed' => , 'in_progress'
            'progress' => 0, // Percentage of course completed
            'started_at' => null,
            'completed_at' => null,
            'last_accessed_at' => null,
            'certificated_at' => null, // Timestamp when the certificate was issued
            'certificate_url' => null, // URL to the completion certificate if applicable
            'score_final' => null, // Final score if applicable
        ]);


        return $team;
    }
}
