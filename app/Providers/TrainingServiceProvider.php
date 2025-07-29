<?php

namespace App\Providers;

use App\Repositories\CourseRepository;
use App\Repositories\Interfaces\CourseInterface;
use App\Services\Quality\TrainingService;
use Illuminate\Support\ServiceProvider;

class TrainingServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function provides()
    {
        return [
            CourseInterface::class,
            TrainingService::class,
        ];
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        // Repositorios
        $this->app->bind(
            CourseInterface::class,
            CourseRepository::class
        );

        // Servicios

        $this->app->singleton(TrainingService::class, function ($app) {
            return new TrainingService(
                $app->make(CourseInterface::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
