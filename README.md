# Documentación - MCE / Medicamentos de control especial

### Asignación de roles

Se debe asignar un solo rol a cada usuario y a cada rol asignar los permisos necesarios. No asignar más de un rol a un usuario.

### Trait HasTeamRoles

El trait HasTeamRoles agrega dos métodos útiles para manejar roles y permisos de usuarios en equipos (teams) dentro de una aplicación multi-tenant (multi-equipo):

1. getCurrentTeamRole()
¿Qué hace?
Obtiene el rol del usuario para el equipo (tenant) actual.
¿Cómo lo hace?
Usa Filament::getTenant() para obtener el equipo actual.
Si no hay equipo, retorna null y registra un warning en el log.
Busca el primer rol del usuario que esté asociado a ese equipo (usando la relación roles() y filtrando por team_id).
Permite que el rol sea global (roles.team_id nulo) o específico del equipo.
2. hasTeamPermission($permissionName)
¿Qué hace?
Verifica si el rol del usuario para el equipo actual tiene un permiso específico.
¿Cómo lo hace?
Llama a getCurrentTeamRole() para obtener el rol del usuario en el equipo actual.
Si hay rol, revisa si ese rol tiene el permiso con el nombre dado ($permissionName).

# Migraciones

El campo 'team_id' de la tabla 'model_has_roles' debe ser transformado para que acepte NULL para poder que funcion el 'Super-Admin'.

# Export y data base notifications por team_id

La exportación de datos envía trabajos a colas y genera database notifications. Se debió
- adicionar 'team_id' a la tabla 'notifications'
- Crear el modelo 'TeamNotification' que extiente a 'use Illuminate\Notifications\DatabaseNotification as BaseNotification'
- Cear el 'EnsureTeamContext' middleware
- modificar el exporter ProductExporter para filtrar datos por 'team_i'
- se sobreescribieron los métodos de notificaciones en el modelo User

# Tabla de contenido para mostrar en orden y de manera accesible las secciones del acta de IVC
Propuesta de interfaz con “Tabla de Contenidos” ordenada
1. Estructura de datos y modelos
Para que la app presente cada sección en el orden exacto del acta, modela las “Secciones” como entidad:
- Tabla sections
- id
- title (e.g. “Proceso de adquisición”)
- order (entero: 1, 2, 3…)
- Relaciones
- Una Audit tiene muchas SectionEntry (evidencias por sección)
- Cada SectionEntry pertenece a una Section
Esto te permite:
- Cambiar orden sin tocar código
- Generar CRUD de secciones si en el futuro varía el acta
3. Dashboard con Livewire + FilamentPHP
3.1. Componente Livewire “TableOfContents”
Crea un componente que:
- Consulta las secciones ordenadas:
public function mount()
{
    $this->sections = Section::orderBy('order')->get();
}
- Renderiza una lista de links ancla:
<div class="space-y-2">
  @foreach($sections as $section)
    <a href="#section-{{ $section->id }}"
       class="block px-2 py-1 hover:bg-gray-100 rounded">
      {{ $section->order }}. {{ $section->title }}
    </a>
  @endforeach
</div>
3.2. En la página Dashboard de Filament
- Registra tu componente en dashboard():
protected function getWidgets(): array
{
    return [
        \App\Filament\Widgets\TableOfContents::class,
        // otros widgets…
    ];
}
- En la vista principal (blade o Livewire), monta cada sección con un div id="section-{{ $id }}"
@foreach($sections as $section)
  <div id="section-{{ $section->id }}" class="mt-8">
    <h2 class="text-xl font-bold">{{ $section->order }}. {{ $section->title }}</h2>
    @livewire('audit.section-entry-form', ['section' => $section])
  </div>
@endforeach
- El componente audit.section-entry-form gestiona el formulario/evidencia para cada sección.

Se pone la tabla de contenido en el dashboard ActaDashboard

## Recepción técnica

Para hacer la recepción técnica se requiere una purchase, no se puede crear desde cero, ajustar