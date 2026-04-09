<?php

namespace App\Providers;

use App\Models\AnesthesiaSheet;
use App\Models\Api\ExternalOrder;
use App\Models\DispatchItems;
use App\Models\Document;
use App\Models\Quality\Training\Course as TrainingCourse;
use App\Models\Quality\Training\Enrollment as TrainingEnrollment;
use App\Models\Quality\Training\Lesson as TrainingLesson;
use App\Models\Quality\Training\Module as TrainingModule;
use Filament\Facades\Filament;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Quality\Records\Improvement\ChecklistItemAnswer as ImprovementChecklistItemAnswer;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;
use App\Models\Team;
use App\Observers\DispatchItemsObserver;
use App\Observers\SaleItemObserver;
use App\Observers\SaleObserver;
use App\Observers\Quality\Training\CourseObserver as TrainingCourseObserver;
use App\Observers\Quality\Training\EnrollmentObserver as TrainingEnrollmentObserver;
use App\Observers\Quality\Training\LessonObserver as TrainingLessonObserver;
use App\Observers\Quality\Training\ModuleObserver as TrainingModuleObserver;
use App\Services\CartService;
use App\Services\InvoiceService;
use App\Services\SaleService;
use Illuminate\Support\Facades\Gate;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\TeamNotification;
use App\Observers\AnesthesiaSheetObserver;
use App\Observers\DocumentObserver;
use App\Observers\ExternalOrderObserver;
use App\Observers\ChecklistItemAnswerObserver;
use App\Services\Quality\Records\Products\MissingProductService;
use App\Services\IndicatorService;
use App\Services\ExternalOrderActionService;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Repositories\RoleRepository::class, function ($app) {
            return new \App\Repositories\RoleRepository();
        });

        $this->app->singleton(\App\Services\RoleService::class, function ($app) {
            return new \App\Services\RoleService($app->make(\App\Repositories\RoleRepository::class));
        });
        // Registramos CartService como singleton para
        // que siempre se utilice la misma instancia durante la petición.
        $this->app->singleton(CartService::class, function ($app) {
            return new CartService();
        });

        // Registramos InvoiceService
        $this->app->singleton(InvoiceService::class, function ($app) {
            return new InvoiceService();
        });

        // Registramos SaleService y le inyectamos InvoiceService mediante $app->make()
        $this->app->singleton(SaleService::class, function ($app) {
            return new SaleService($app->make(InvoiceService::class));
        });

        $this->app->singleton(IndicatorService::class, function ($app) {
            return new IndicatorService();
        });

        $this->app->singleton(MissingProductService::class, function ($app) {
            return new MissingProductService();
        });

        // Registramos ExternalOrderActionService como singleton
        $this->app->singleton(ExternalOrderActionService::class, function ($app) {
            return new ExternalOrderActionService();
        });

        $this->app->bind(DatabaseNotification::class, TeamNotification::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            // Usamos isAdmin() en lugar de hasRole() para el bypass global.
            // Esto permite que el admin entre a tenantManager sin importar el team_id.
            return $user->isSuperAdmin() ? true : null;
        });

        FilamentView::registerRenderHook(
            PanelsRenderHook::FOOTER,
            fn(): string => Blade::render('@livewire(\'footer-text-component\')'),
        );
        // Observers
        DispatchItems::observe(DispatchItemsObserver::class);
        Sale::observe(SaleObserver::class);
        SaleItem::observe(SaleItemObserver::class);
        Document::observe(DocumentObserver::class);
        ExternalOrder::observe(ExternalOrderObserver::class);
        ImprovementChecklistItemAnswer::observe(ChecklistItemAnswerObserver::class);
        TrainingCourse::observe(TrainingCourseObserver::class);
        TrainingModule::observe(TrainingModuleObserver::class);
        TrainingLesson::observe(TrainingLessonObserver::class);
        TrainingEnrollment::observe(TrainingEnrollmentObserver::class);
    }
}
