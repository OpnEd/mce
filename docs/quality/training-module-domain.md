# Modulo de Capacitacion: Dominio Oficial

Fecha: 2026-04-07

## Decision

El dominio oficial y vigente del modulo de capacitacion es el namespace `App\Models\Quality\Training`.

La capa activa del modulo esta compuesta por estas entidades:

- `Course`
- `Module`
- `Lesson`
- `Assessment`
- `Question`
- `QuestionOption`
- `Enrollment`
- `EnrollmentLesson`
- `AssessmentAttempt`
- `UserAnswer`
- `Certificate`
- `AuditLog`

Estas entidades son las que hoy soportan el flujo real del producto:

- administracion en Filament
- inscripcion de usuarios
- consumo de lecciones
- intentos de evaluacion
- progreso por leccion
- finalizacion del curso
- certificados y auditoria

## Capa Legacy

Los modelos `App\Models\Training`, `App\Models\TrainingCategory` y `App\Models\EvaluationRecord` quedan clasificados como compatibilidad legacy.

Su presencia actual se conserva para:

- compatibilidad con datos antiguos
- relaciones heredadas en `Team`
- transicion controlada mientras se termina la normalizacion del modulo

Estos modelos ya no deben usarse para nuevas funcionalidades, nuevas pantallas ni nuevas integraciones del modulo de capacitacion.

## Regla de Desarrollo

Desde esta fecha, cualquier cambio funcional del modulo debe implementarse sobre la capa `App\Models\Quality\Training`.

Si se necesita leer o migrar informacion de la capa legacy, debe hacerse como compatibilidad o migracion de datos, no como extension del dominio activo.

## Consecuencias

1. Las correcciones de evaluaciones, progreso, certificados, observers, resources y multitenancy se haran exclusivamente sobre la capa `Quality\Training`.
2. Las referencias legacy se iran retirando o aislando de manera progresiva.
3. Cualquier inconsistencia detectada entre ambas capas se resolvera favoreciendo la capa `Quality\Training`.
