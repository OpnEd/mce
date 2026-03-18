<?php

namespace App\Filament\TenantManager\Pages;

use App\Models\Team;
use App\Services\TeamSetupService;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Populate extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'phosphor-database';
    protected static string $view = 'filament.tenant-manager.pages.populate';
    protected static ?string $title = 'Poblar Team desde config';

    public ?array $formData = [
        'team_id' => null,
        'config_key' => null,
    ];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getConfigOptions(): array
    {
        return app(TeamSetupService::class)->getPopulateConfigOptions();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('team_id')
                    ->label('Seleccione Team')
                    ->options(Team::query()->orderBy('name')->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->required(),
                Select::make('config_key')
                    ->label('Archivo de configuración a aplicar')
                    ->options($this->getConfigOptions())
                    ->required(),
            ])
            ->statePath('formData');
    }

    public function populateSelected()
    {
        $data = $this->form->getState();
        $teamId = (int) ($data['team_id'] ?? 0);
        $configKey = $data['config_key'] ?? null;

        if (! $teamId || ! $configKey) {
            Notification::make()
                ->title('Formulario incompleto')
                ->danger()
                ->body('Debes seleccionar un team y un archivo de configuración.')
                ->send();
            return;
        }

        $team = Team::find($teamId);
        if (! $team) {
            Notification::make()
                ->title('Team no encontrado')
                ->danger()
                ->send();
            return;
        }

        try {
            //dd($team, $configKey);
            DB::transaction(function () use ($team, $configKey) {
                $this->handlePopulate($team, $configKey);
            });

            $allOptions = $this->getConfigOptions();
            $flatOptions = array_merge(...array_values($allOptions));
            $configLabel = $flatOptions[$configKey] ?? $configKey;

            Notification::make()
                ->title('Población completada')
                ->success()
                ->body("Se aplicó correctamente '{$configLabel}' al team '{$team->name}'.")
                ->send();
        } catch (\Throwable $e) {
            Log::error('Error al poblar team desde config', [
                'team_id' => $team->id,
                'config' => $configKey,
                'error' => $e->getMessage(),
            ]);

            Notification::make()
                ->title('Error durante el poblamiento')
                ->danger()
                ->body("Ocurrió un error: " . $e->getMessage())
                ->send();

            throw $e;
        }
    }

    protected function handlePopulate(Team $team, string $configKey): void
    {
        $actor = Auth::user();

        app(TeamSetupService::class)->populateByConfigKey($team, $configKey, $actor);
    }
}
