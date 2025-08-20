<div>
    <label class="flex items-center gap-3">
        <x-filament::input.checkbox wire:model="{{ $getStatePath() }}" />
        <div>
            <x-filament::modal width="5xl">
                <x-slot name="heading">
                    Términos y Condiciones - Política de Tratamiento de Datos Personales
                </x-slot>
                <x-slot name="trigger">
                    <a href="#" class="text-sm font-medium leading-6 text-gray-950 underline dark:text-white">
                        He leído y acepto los <strong>Términos & Condiciones</strong> y la <strong>Política de Tratamiento de Datos Personales</strong>.</a>
                </x-slot>

                <div class="prose dark:prose-invert max-w-none">
                    {!! $policy_terms !!}
                </div>
            </x-filament::modal>
        </div>
    </label>
</div>
