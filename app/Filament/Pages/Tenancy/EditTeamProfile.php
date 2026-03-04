<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;

class EditTeamProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Datos del establecimiento';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('team-registration')
                    ->persistTabInQueryString() // opcional, pero mejora UX si recarga
                    ->tabs([
                        Tab::make('Información básica')
                            ->schema([
                                Section::make('Datos de la droguería')
                                    ->columns([
                                        'default' => 1,
                                        'md' => 2,
                                    ])
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Nombre de la droguería')
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('team_name')
                                            ->label('Razón social')
                                            ->helperText('Nombre legal / razón social')
                                            ->nullable()
                                            ->maxLength(255),

                                        TextInput::make('identification')
                                            ->label('NIT')
                                            ->helperText('Pon una copia del RUT y/o la Cámara de Comercio como primer documento en el folder bajo la etiqueta NIT')
                                            ->required()
                                            ->maxLength(35),

                                        TextInput::make('registration_number')
                                            ->label('Registro (Negocios saludables, negocios rentables)')
                                            ->helperText('Número de registro del programa')
                                            ->nullable()
                                            ->maxLength(15),

                                        TextInput::make('establishment_id')
                                            ->label('ID establecimiento (SDS)')
                                            ->helperText('Identificación en la base de datos de la SDS')
                                            ->nullable()
                                            ->maxLength(15),
                                    ]),

                                Section::make('Contacto')
                                    ->columns([
                                        'default' => 1,
                                        'md' => 2,
                                    ])
                                    ->schema([
                                        TextInput::make('email')
                                            ->label('E-mail')
                                            ->email()
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('phonenumber')
                                            ->label('Teléfono (principal)')
                                            ->tel()
                                            ->required()
                                            ->maxLength(35),

                                        TextInput::make('phone_number_1')
                                            ->label('Teléfono 1 (adicional)')
                                            ->tel()
                                            ->nullable()
                                            ->maxLength(35),

                                        TextInput::make('phone_number_2')
                                            ->label('Teléfono 2 (adicional)')
                                            ->tel()
                                            ->nullable()
                                            ->maxLength(35),
                                    ]),
                            ]), // Información básica

                        Tab::make('Ubicación')
                            ->schema([
                                Section::make('Dirección y sede')
                                    ->columns([
                                        'default' => 1,
                                        'md' => 2,
                                    ])
                                    ->schema([
                                        TextInput::make('address')
                                            ->label('Dirección')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        TextInput::make('location_1')
                                            ->label('Sede (nombre o identificación)')
                                            ->helperText('Si aplica (por ejemplo: Sede 1, Punto CC, etc.)')
                                            ->nullable()
                                            ->maxLength(255),

                                        TextInput::make('location_2')
                                            ->label('Ubicación dentro de (CC / conjunto / otro)')
                                            ->helperText('Si aplica (ej.: “Centro Comercial X”, “Conjunto Y”)')
                                            ->nullable()
                                            ->maxLength(255),
                                    ]),

                                Section::make('Zona')
                                    ->columns([
                                        'default' => 1,
                                        'md' => 3,
                                    ])
                                    ->schema([
                                        TextInput::make('town')
                                            ->label('Localidad')
                                            ->nullable()
                                            ->maxLength(255),

                                        TextInput::make('upz')
                                            ->label('UPZ')
                                            ->helperText('Unidad de planeación zonal')
                                            ->nullable()
                                            ->maxLength(255),

                                        TextInput::make('neighborhood')
                                            ->label('Barrio')
                                            ->nullable()
                                            ->maxLength(255),
                                    ]),
                            ]), // Ubicación

                        Tab::make('Representante legal')
                            ->schema([
                                Section::make('Datos del representante')
                                    ->columns([
                                        'default' => 1,
                                        'md' => 2,
                                    ])
                                    ->schema([
                                        TextInput::make('legal_representative_name')
                                            ->label('Nombre del representante legal')
                                            ->helperText('Si no aplica, dejar en blanco esta sección')
                                            ->nullable()
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        // Si prefieres Select, cámbialo; aquí lo dejo como enum input simple según tu migración.
                                        // Filament suele recomendar Select para enums, pero TextInput funciona.
                                        \Filament\Forms\Components\Select::make('legal_representative_doc_type')
                                            ->label('Tipo de documento')
                                            ->options([
                                                'CC' => 'CC',
                                                'CE' => 'CE',
                                                'PEP' => 'PEP',
                                            ])
                                            ->nullable(),

                                        TextInput::make('legal_representative_doc_num')
                                            ->label('Número de documento')
                                            ->helperText('Pon una copia del documento de identidad del Representante Legal como segundo documento en la "sección cédula del establecimiento" en el folder bajo la etiqueta "Representante Legal" si aplica')
                                            ->nullable()
                                            ->maxLength(35),
                                    ]),
                            ]), // Representante legal

                        Tab::make('Horarios')
                            ->schema([
                                Section::make('Horario de atención')
                                    ->schema([
                                        Textarea::make('operating_hours')
                                            ->label('Horario')
                                            ->helperText('Ej.: L–V 8:00–18:00, S 9:00–13:00')
                                            ->nullable()
                                            ->rows(4)
                                            ->columnSpanFull(),
                                    ]),
                            ]), // Horarios
                    ]),
            ]);
    }
}
