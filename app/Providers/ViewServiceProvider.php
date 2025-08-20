<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * Aquí es donde se registran los view composers de la aplicación.
     * Los view composers son callbacks o métodos de clase que se llaman
     * cuando una vista específica es renderizada.
     */
    public function boot(): void
    {
        // Este view composer se asocia con la vista 'livewire.terms-and-conditions'.
        // Cada vez que esta vista se renderice, la lógica dentro del closure se ejecutará,
        // inyectando datos directamente en la vista.
        View::composer('livewire.terms-and-conditions', function ($view) {
            // Define las rutas a los archivos Markdown que contienen los textos legales.
            // Nota: Se han ajustado los nombres de archivo para que coincidan con los archivos proporcionados.
            $path = resource_path('views/markdown/policy_terms.md');

            // Verifica si el archivo de políticas existe.
            // Si existe, lee su contenido, lo convierte de Markdown a HTML y lo asigna a la variable $policy.
            // Si no existe, asigna un mensaje de error.
            $content = File::exists($path)
                ? Str::markdown(File::get($path))
                : 'Documento no disponible.';

            // Pasa las variables a la vista.
            // Se envían tanto en un array 'docs' como individualmente para mantener
            // la compatibilidad con diferentes formas de acceder a los datos en la vista.
            $view->with('docs', ['policy_terms' => $path]);
            $view->with('policy_terms', $content);
        });
    }
}
