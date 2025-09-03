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

        // Poblando indicadores de gestión

        $indicators = config('management-indicators');

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

        // POBLAMIENTO AUTOMÁTICO DE SECCIONES Y PREGUNTAS DE IVC
        $sections = config('minutes-ivc-sections');

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

        // Define el mapeo sección => variable de id
        /* $sectionNames = [
            'Cédula del establecimiento' => 'minutes_ivc_section_ced_est_id',
            'Recurso Humano' => 'minutes_ivc_section_rec_hum_id',
            'Infraestructura Física' => 'minutes_ivc_section_infr_fis_id',
            'Saneamiento de edificaciones' => 'minutes_ivc_section_san_edif_id',
            'Áreas' => 'minutes_ivc_section_areas_id',
            'Clasificación del Establecimiento' => 'minutes_ivc_section_clasif_estab_id',
            'Servicios Ofrecidos' => 'minutes_ivc_section_serv_ofr_id',
            'Otros aspectos' => 'minutes_ivc_section_otr_asp_id',
            'Sistema de gestión de calidad' => 'minutes_ivc_section_gest_cal_id',
            'Selección' => 'minutes_ivc_section_selec_id',
            'Adquisición' => 'minutes_ivc_section_adq_id',
            'Recepción' => 'minutes_ivc_section_recep_id',
            'Almacenamiento' => 'minutes_ivc_section_almac_id',
            'Dispensación' => 'minutes_ivc_section_dispe_id',
            'Devoluciones' => 'minutes_ivc_section_devol_id',
            'Manejo de Medicamentos Cadena de Frío' => 'minutes_ivc_section_cad_fri_id',
            'Inyectología' => 'minutes_ivc_section_inyect_id',
        ]; */

        // Obtén los IDs en un solo ciclo
        /* $sectionIds = [];
        foreach ($sectionNames as $sectionName => $varName) {
            $sectionIds[$varName] = MinutesIvcSection::where('team_id', $team->id)
                ->where('name', $sectionName)
                ->first()
                ?->id;
        } */

        // Mapeo de variable de sección => nombre de archivo de configuración
       /*  $sectionConfigMap = [
            'minutes_ivc_section_ced_est_id'      => 'minutes-ivc-first-section-entries',
            'minutes_ivc_section_rec_hum_id'      => 'minutes-ivc-second-section-entries',
            'minutes_ivc_section_infr_fis_id'     => 'minutes-ivc-third-section-entries',
            'minutes_ivc_section_san_edif_id'     => 'minutes-ivc-fourth-section-entries',
            'minutes_ivc_section_areas_id'        => 'minutes-ivc-fifth-section-entries',
            'minutes_ivc_section_clasif_estab_id' => 'minutes-ivc-sixth-section-entries',
            'minutes_ivc_section_serv_ofr_id'     => 'minutes-ivc-seventh-section-entries',
            'minutes_ivc_section_inyect_id'       => 'minutes-ivc-inyectologia-section-entries',
            'minutes_ivc_section_otr_asp_id'      => 'minutes-ivc-eighth-section-entries',
            'minutes_ivc_section_gest_cal_id'     => 'minutes-ivc-nine-section-entries',
            'minutes_ivc_section_selec_id'        => 'minutes-ivc-tenth-section-entries',
            'minutes_ivc_section_adq_id'          => 'minutes-ivc-eleventh-section-entries',
            'minutes_ivc_section_recep_id'        => 'minutes-ivc-twelveth-section-entries',
            'minutes_ivc_section_almac_id'        => 'minutes-ivc-thirteenth-section-entries',
            'minutes_ivc_section_dispe_id'        => 'minutes-ivc-fourteenth-section-entries',
            'minutes_ivc_section_devol_id'        => 'minutes-ivc-fifteenth-section-entries',
            'minutes_ivc_section_cad_fri_id'      => 'minutes-ivc-sixteenth-section-entries',
        ]; */

        // Recorre el mapeo y pobla cada sección automáticamente
        /* foreach ($sectionConfigMap as $sectionVar => $configName) {
            $sectionId = $sectionIds[$sectionVar] ?? null;
            $entries = config($configName, []);
            if ($sectionId && is_array($entries)) {
                foreach ($entries as $e) {
                    \App\Models\MinutesIvcSectionEntry::updateOrCreate(
                        [
                            'minutes_ivc_section_id' => $sectionId,
                            'apply' => $e['apply'] ?? true,
                            'entry_id' => $e['entry_id'] ?? null,
                            'criticality' => $e['criticality'] ?? null,
                            'question' => $e['question'] ?? null,
                            'answer' => $e['answer'] ?? null,
                            'entry_type' => $e['entry_type'] ?? null,
                            'links' => $e['links'] ?? null,
                            'compliance' => $e['compliance'] ?? null,
                        ]
                    );
                }
            }
        } */

        //  POBLAMIENTO AUTOMÁTICO DE ROLES Y PERMISOS

        $permissionNames = [];
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

        //  POBLAMIENTO AUTOMÁTICO DE CRONOGRAMA DE CAPACITACIÓN

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

        // POBLAMIENTO AUTOMÁTICO DE MATRÍCULA EN CURSO INICIAL
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
