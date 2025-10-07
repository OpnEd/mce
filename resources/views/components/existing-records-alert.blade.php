<!-- resources/views/components/existing-records-alert.blade.php -->
@php
    $urlParaLaLista = \App\Filament\Resources\Quality\Records\Cleaning\CleaningRecordResource::getUrl('index');
    $urlParaLaTabla = \App\Filament\Resources\Quality\Records\Cleaning\CleaningRecordResource::getUrl('table');
@endphp
<div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3 flex-1">
            <h3 class="text-sm font-medium text-amber-800">
                Registros Existentes Detectados
            </h3>
            <div class="mt-2 text-sm text-amber-700">
                <p>
                    Ya existe(n) <strong>{{ $count }}</strong> registro(s) de limpieza para el día <strong>{{ $date }}</strong>.
                </p>
                <p class="mt-1">
                    Esto es normal si trabajas con múltiples turnos. Asegúrate de:
                </p>
                <ul class="mt-2 ml-4 list-disc space-y-1">
                    <li>Seleccionar el <strong>turno correcto</strong> para evitar duplicados</li>
                    <li>Verificar que las áreas no se solapen con registros anteriores</li>
                    <li>Agregar <strong>notas del turno</strong> si es necesario</li>
                </ul>
            </div>
            <hr>
            <div class="mt-4 flex space-x-2">
                <a href="{{ $urlParaLaLista }}" 
                   class="bg-amber-100 hover:bg-amber-200 text-amber-800 text-xs font-medium px-3 py-1 rounded-md">
                    📋 Ver Registros Existentes
                </a>
                <a href="{{ $urlParaLaTabla }}" 
                   class="bg-amber-100 hover:bg-amber-200 text-amber-800 text-xs font-medium px-3 py-1 rounded-md">
                    🗓️ Vista Tabla del Día
                </a>
            </div>
        </div>
    </div>
</div>