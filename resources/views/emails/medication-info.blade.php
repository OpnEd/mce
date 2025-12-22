<x-mail::message>

# Uso seguro y racional de medicamentos

@isset($clientName)
**Sr(a) Usuario(a),** {{ $clientName }}
@endisset

## Para usar adecuadamente **{{ $medication['name'] }}**, tenga en cuenta:

**Advertencia importante:** {{ $medication['adherence_warning'] ?? 'Siga las indicaciones de su profesional de la salud.' }}


---


## Reconstitución
{{ $medication['reconstitution'] ?? 'No aplica.' }}


## Medición de la dosis
{{ $medication['dose_measurement'] ?? 'Consulte la etiqueta o la prescripción.' }}


## Almacenamiento
{{ $medication['storage'] ?? 'Consulte las instrucciones.' }}


## Disposición final
{{ $medication['disposal'] ?? 'Entregar sobrantes o vencidos en la droguería.' }}


## Qué hacer en caso de reacciones adversas
{{ $medication['adverse_event_warning'] ?? 'Regrese a la droguería o consulte a su médico.' }}


---

@if(isset($teamName, $teamPhone, $teamEmail))
Si requiere más información o tiene dudas, escriba a {{ $teamEmail}}
o comuníquese con la droguería {{ $teamName }} al número {{ $teamPhone }}.

@endif


Gracias por confiar en nosotros,


**{{ $teamName ?? config('app.name') }}**

{{-- <x-mail::button :url="''">
Visita nuestra droguería en línea
</x-mail::button> --}}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
