<?php

namespace App\Filament\CustomerPanel\Pages;

use App\Models\Quality\Records\Clients\ClientSatisfactionEvaluation as ClientSatisfactionEvaluationModel;
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

class ClientSatisfactionEvaluation extends \Filament\Pages\Dashboard implements HasForms
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
        return __('Encuesta de satisfaccion');
    }

    public static function getRoutePath(): string
    {
        return '/encuesta-de-satisfaccion/{team}';
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::ScreenLarge;
    }

    public function form(Form $form): Form
    {
        $scoreOptions = [
            1 => '1 - Muy insatisfecho',
            2 => '2 - Insatisfecho',
            3 => '3 - Neutral',
            4 => '4 - Satisfecho',
            5 => '5 - Muy satisfecho',
        ];

        $recommendationOptions = [
            0 => '0',
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',
            7 => '7',
            8 => '8',
            9 => '9',
            10 => '10',
        ];

        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Datos de la evaluacion')
                        ->icon('phosphor-clipboard-text')
                        ->schema([
                            Forms\Components\Select::make('service_area')
                                ->label('Servicio evaluado')
                                ->options(ClientSatisfactionEvaluationModel::getServiceAreas()),
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

                    Wizard\Step::make('Calificaciones')
                        ->icon('phosphor-seal-check')
                        ->schema([
                            Forms\Components\Grid::make(3)
                                ->schema([
                                    Forms\Components\Select::make('overall_score')
                                        ->label('Satisfaccion general')
                                        ->options($scoreOptions)
                                        ->required(),
                                    Forms\Components\Select::make('attention_score')
                                        ->label('Atencion del personal')
                                        ->options($scoreOptions),
                                    Forms\Components\Select::make('waiting_time_score')
                                        ->label('Tiempo de espera')
                                        ->options($scoreOptions),
                                    Forms\Components\Select::make('availability_score')
                                        ->label('Disponibilidad de productos')
                                        ->options($scoreOptions),
                                    Forms\Components\Select::make('information_clarity_score')
                                        ->label('Claridad de la informacion')
                                        ->options($scoreOptions),
                                    Forms\Components\Select::make('cleanliness_score')
                                        ->label('Limpieza y orden')
                                        ->options($scoreOptions),
                                    Forms\Components\Select::make('facility_score')
                                        ->label('Comodidad del establecimiento')
                                        ->options($scoreOptions),
                                    Forms\Components\Select::make('recommendation_score')
                                        ->label('Recomendacion (0-10)')
                                        ->options($recommendationOptions),
                                ]),
                            Forms\Components\Toggle::make('would_recommend')
                                ->label('Recomendaria el servicio'),
                            Forms\Components\Toggle::make('would_return')
                                ->label('Volveria a usar el servicio'),
                            Forms\Components\Toggle::make('follow_up_required')
                                ->label('Requiere seguimiento')
                                ->default(false),
                        ]),

                    Wizard\Step::make('Comentarios')
                        ->icon('phosphor-chat-centered-text')
                        ->schema([
                            Forms\Components\Grid::make(3)
                                ->schema([
                                    Forms\Components\Textarea::make('comments')
                                        ->label('Comentarios')
                                        ->rows(3)
                                        ->columnSpanFull(),
                                ])->columns(2),

                        ]),
                ])->submitAction(new HtmlString(Blade::render(<<<BLADE
                            <x-filament::button
                                type="submit"
                                size="sm"
                            >
                                Guardar
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
                ->title('No se encontro el equipo para registrar la encuesta.')
                ->danger()
                ->send();

            return;
        }

        $team = $this->team ?? Team::find($this->teamId);

        if (! $team || $team->id !== $this->teamId) {
            Notification::make()
                ->title('No se encontro el equipo para registrar la encuesta.')
                ->danger()
                ->send();

            return;
        }

        try {
            DB::transaction(function () use ($data): void {
                $survey = new ClientSatisfactionEvaluationModel();
                $survey->fill($data);
                $survey->team_id = $this->teamId;
                $survey->user_id = null;
                $survey->evaluated_at = now();
                $survey->channel = 'digital';

                if ($survey->is_anonymous) {
                    $survey->client_name = null;
                    $survey->client_document = null;
                    $survey->client_phone = null;
                    $survey->client_email = null;
                }

                $survey->save();
            });

            $this->form->fill([]);
            $this->data = [];

            Notification::make()
                ->title('Encuesta registrada exitosamente')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Error al registrar la encuesta')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected static ?string $navigationIcon = 'phosphor-list-checks';
    protected static ?string $navigationLabel = 'Encuesta de satisfaccion';
    protected static ?string $slug = 'encuesta-de-satisfaccion';
    protected static ?string $title = 'Encuesta de satisfaccion';
    protected static string $view = 'filament.customer-panel.pages.client-satisfaction-evaluation';
    protected static bool $shouldRegisterNavigation = false;
}
