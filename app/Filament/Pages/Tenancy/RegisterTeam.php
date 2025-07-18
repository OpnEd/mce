<?php

namespace App\Filament\Pages\Tenancy;

use App\Enums\PermissionType;
use App\Models\ManagementIndicator;
use App\Models\MinutesIvcSection;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Team;
use Database\Seeders\ManagementIndicatorTeamSeeder;
use Dom\Text;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
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

        $team->users()->attach(\Illuminate\Support\Facades\Auth::user());

        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
            'team_id' => $team->id,
        ]);

        // 4) ¡Muy importante! Configura el team activo para Spatie:
        app(PermissionRegistrar::class)
            ->setPermissionsTeamId($team->id);

        // 5) Asigna el rol **sobre el modelo User**, no sobre su ID
        $user = Auth::user();
        $user->assignRole($role);


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
                        'role_id' => $role->id,
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
        $role->syncPermissions($permissionNames);


        return $team;
    }
}
