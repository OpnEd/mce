<?php

namespace App\Filament\CustomerPanel\Pages;

use App\Models\Quality\Records\Clients\ClientPqrsRecord as ClientPqrsRecordModel;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

class ClientPqrsRecord extends \Filament\Pages\Dashboard implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    public ?Team $team = null;
    public ?int $teamId = null;

    public function mount(): void
    {
        $teamFromRoute = request()->route('team');

        if ($teamFromRoute instanceof Team) {
            $this->team = $teamFromRoute;
            $this->teamId = $teamFromRoute->id;
        } elseif ($teamFromRoute) {
            $this->teamId = (int) $teamFromRoute;
            $this->team = Team::find($this->teamId);
        } else {
            $teamId = request()->query('team');
            $this->teamId = $teamId ? (int) $teamId : null;
            $this->team = $this->teamId ? Team::find($this->teamId) : null;
        }

        $this->form->fill();
    }

    public function getHeading(): string
    {
        return __('Formulario PQRS');
    }

    public static function getRoutePath(): string
    {
        return '/pqrs/{team}';
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::ScreenLarge;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Detalle PQRS')
                        ->icon('phosphor-clipboard-text')
                        ->schema([
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\Select::make('type')
                                        ->label('Tipo')
                                        ->options(ClientPqrsRecordModel::getTypes())
                                        ->required(),
                                    Forms\Components\TextInput::make('subject')
                                        ->label('Asunto')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\Textarea::make('description')
                                        ->label('Descripcion')
                                        ->rows(4)
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    Wizard\Step::make('Datos del usuario')
                        ->icon('phosphor-user')
                        ->schema([
                            Forms\Components\Toggle::make('is_anonymous')
                                ->label('Registro anonimo')
                                ->live()
                                ->default(false),
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\TextInput::make('client_name')
                                        ->label('Nombre')
                                        ->maxLength(255)
                                        ->hidden(fn (Get $get): bool => (bool) $get('is_anonymous')),
                                    Forms\Components\TextInput::make('client_document')
                                        ->label('Documento')
                                        ->maxLength(100)
                                        ->hidden(fn (Get $get): bool => (bool) $get('is_anonymous')),
                                    Forms\Components\TextInput::make('client_phone')
                                        ->label('Telefono')
                                        ->tel()
                                        ->maxLength(50)
                                        ->hidden(fn (Get $get): bool => (bool) $get('is_anonymous')),
                                    Forms\Components\TextInput::make('client_email')
                                        ->label('Correo')
                                        ->email()
                                        ->maxLength(255)
                                        ->hidden(fn (Get $get): bool => (bool) $get('is_anonymous')),
                                ]),
                        ]),
                ])->submitAction(new HtmlString(Blade::render(<<<BLADE
                            <x-filament::button
                                type="submit"
                                size="sm"
                            >
                                Enviar PQRS
                            </x-filament::button>
                        BLADE)))
            ])
            ->statePath('data');
    }

    public function store(): void
    {
        $data = $this->form->getState();

        if (! $this->teamId) {
            Notification::make()
                ->title('No se encontro el equipo para registrar la PQRS.')
                ->danger()
                ->send();

            return;
        }

        $team = $this->team ?? Team::find($this->teamId);

        if (! $team || $team->id !== $this->teamId) {
            Notification::make()
                ->title('No se encontro el equipo para registrar la PQRS.')
                ->danger()
                ->send();

            return;
        }

        try {
            DB::transaction(function () use ($data): void {
                $record = new ClientPqrsRecordModel();
                $record->fill($data);
                $record->team_id = $this->teamId;
                $record->user_id = null;
                $record->received_at = now();
                $record->channel = 'digital';
                $record->priority = 'media';
                $record->status = 'recibido';

                $days = ClientPqrsRecordModel::getDefaultResponseDaysByType($record->type);
                $record->response_time_limit_days = $days;
                $record->response_due_at = $days ? $record->received_at->copy()->addDays($days) : null;

                if ($record->is_anonymous) {
                    $record->client_name = null;
                    $record->client_document = null;
                    $record->client_phone = null;
                    $record->client_email = null;
                }

                $record->save();
            });

            $this->form->fill([]);
            $this->data = [];

            Notification::make()
                ->title('PQRS registrada exitosamente')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Error al registrar la PQRS')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected static ?string $navigationIcon = 'phosphor-chat-dots';
    protected static ?string $navigationLabel = 'PQRS';
    protected static ?string $slug = 'pqrs';
    protected static ?string $title = 'PQRS';
    protected static string $view = 'filament.customer-panel.pages.client-pqrs-record';
    protected static bool $shouldRegisterNavigation = false;
}
