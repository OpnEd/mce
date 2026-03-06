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


## Refactor Tenancy Pages (BaseSectionPage)

Se simplificaron las paginas de `app/Filament/Pages/Tenancy` que heredan de `BaseSectionPage` para reducir repeticion.

### Cambios aplicados

- `BaseSectionPage` ahora centraliza configuracion por constantes de clase:
  - `NAVIGATION_LABEL`
  - `NAVIGATION_GROUP`
  - `NAVIGATION_SORT`
  - `SLUG`
  - `VIEW`
  - `SECTION`
  - `TITLE` (opcional)
- Se agregaron/ajustaron los metodos para leer esa configuracion:
  - `getNavigationLabel()`
  - `getNavigationGroup()`
  - `getNavigationSort()`
  - `getSlug()`
  - `getTitle()` (usa `TITLE` o `NAVIGATION_LABEL`)
  - `getView()`
- En `mount()` se asigna `section` desde `static::SECTION`.
- Las paginas de tenancy simples se migraron para definir solo constantes y se eliminaron `use` no utilizados.

### Correccion posterior

- Se corrigio la firma de `getView()` en `BaseSectionPage` para que sea no estatica:
  - de `public static function getView(): string`
  - a `public function getView(): string`
- Motivo: compatibilidad con la firma base de Filament (`BasePage::getView()`), evitando el error:
  - `Cannot make non static method Filament\Pages\BasePage::getView() static in class App\Filament\Pages\BaseSectionPage`


## Redireccionamiento a document.details en la vista field-row

Funciona en dos capas: resolución previa (backend) y fallback en la vista.

1. El `entry` llega a `field-row` con `resolved_links` desde la página.
En [quality-management.blade.php](C:\Users\PAOLA\Herd\d-origin2.0.0\resources\views\filament\pages\tenancy\quality-management.blade.php:27) se pasa:
`'links' => $entry['resolved_links'] ?? []`.

2. `resolved_links` se construye en backend con `LinkResolver`.
En [BaseSectionPage.php](C:\Users\PAOLA\Herd\d-origin2.0.0\app\Filament\Pages\BaseSectionPage.php:98) se ejecuta `resolve(...)`.

3. Si `key = document.slug`, el resolvedor genera la URL con `document.details`.
En [LinkResolver.php](C:\Users\PAOLA\Herd\d-origin2.0.0\app\Services\LinkResolver.php:29) detecta `document.slug` y en [LinkResolver.php](C:\Users\PAOLA\Herd\d-origin2.0.0\app\Services\LinkResolver.php:38) usa:
`route('document.details', ['tenant' => $teamId, 'document' => $slug])`.
Con tu ejemplo (`value=procedimiento-de-recepcion-tecnica`), el slug usado es ese valor.

4. Si por alguna razón no vino URL resuelta, `field-row` la reconstruye.
En [field-row.blade.php](C:\Users\PAOLA\Herd\d-origin2.0.0\resources\views\components\settings\field-row.blade.php:115) vuelve a hacer:
`route('document.details', ['tenant' => $tenantId, 'document' => $routeValue])`.

5. Luego renderiza el enlace en la rama `ROUTE`.
En [field-row.blade.php](C:\Users\PAOLA\Herd\d-origin2.0.0\resources\views\components\settings\field-row.blade.php:280) imprime `<a href="...">...</a>`.

6. Esa ruta `document.details` apunta al PDF con binding por slug.
En [web.php](C:\Users\PAOLA\Herd\d-origin2.0.0\routes\web.php:72):
`admin/{tenant}/documents/{document:slug}.pdf`.
Al abrirla, entra a [DocumentController.php](C:\Users\PAOLA\Herd\d-origin2.0.0\app\Http\Controllers\Quality\DocumentController.php:17) y termina haciendo `stream(...)` del PDF en [DocumentController.php](C:\Users\PAOLA\Herd\d-origin2.0.0\app\Http\Controllers\Quality\DocumentController.php:161).

Si el slug `procedimiento-de-recepcion-tecnica` no existe en `documents`, Laravel responde 404 por el route model binding `{document:slug}`.

## Flujo documental implementado (Preparación -> Revisión -> Aprobación)

Se implementó un flujo de control documental usando la estructura existente (`prepared_by`, `reviewed_by`, `approved_by`) para impedir el uso operativo de documentos no aprobados/obsoletos.

### 1) Estado del documento (derivado)

En el modelo `Document` se agregaron estados derivados:
- `preparation`: cuando no tiene `reviewed_by` ni `approved_by`.
- `review`: cuando tiene `reviewed_by` y no `approved_by`.
- `approved`: cuando tiene `approved_by`.

También se agregaron helpers:
- `isInPreparation()`
- `isInReview()`
- `isApproved()`

### 2) Transiciones en Filament (DocumentResource)

En la tabla de documentos se agregó columna de estado y acciones:
- `Enviar a revisión`
- `Marcar revisión`
- `Aprobar`
- `Regresar a preparación`

Reglas de separación de funciones aplicadas:
- Revisor distinto del elaborador.
- Aprobador distinto del elaborador y del revisor.
- No se permite aprobar si falta elaboración o revisión.

### 3) Reaprobación obligatoria ante cambios

En `EditDocument`, si un documento ya aprobado cambia en campos de contenido (título, proceso, categoría, objetivo, alcance, referencias, términos, responsabilidades, registros, procedimiento, anexos, data, etc.), se reinicia automáticamente el ciclo:
- `prepared_by = usuario actual`
- `reviewed_by = null`
- `approved_by = null`

Esto evita que una versión modificada siga apareciendo como aprobada.

### 4) Bloqueo de uso operativo de no aprobados

En `DocumentController@documentDetails`:
- Se valida que el documento pertenezca al tenant de la ruta.
- Si el documento no está aprobado, se bloquea su visualización para operación (`403`), excepto usuarios con permiso `edit-document`.

Con esto, los enlaces operativos (como `document.details`) solo exponen documentos aprobados para uso general.

### 5) Auditoría de cambios

`DocumentObserver` sigue registrando cambios en `document_versions`. Se corrigió un detalle para evitar uso de variable no definida en `comment`.
