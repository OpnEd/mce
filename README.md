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