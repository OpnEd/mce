<?php

namespace App\Filament\Resources\Quality;

use App\Filament\Resources\Quality\ProcessResource\Pages;
use App\Filament\Resources\ProcessResource\RelationManagers;
use App\Models\Process;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Filament\Facades\Filament;
use Filament\GlobalSearch\Actions\Action;
use Illuminate\Validation\Rules\Unique;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Forms\Get;

class ProcessResource extends Resource
{
    protected static ?string $model = Process::class;

    protected static ?int $navigationSort = 15;
    protected static ?string $navigationGroup = 'Plataforma Estratégica';
    protected static ?string $navigationLabel = 'Procesos';
    protected static ?string $pluralModelLabel = 'Procesos';
    protected static ?string $modelLabel = 'Proceso';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $slug = 'procesos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información General')
                    ->schema([
                        Forms\Components\Select::make('process_type_id')
                            ->label('Tipo de Proceso')
                            ->relationship('process_type', 'name')
                            ->required(),
                        Forms\Components\TextInput::make('code')
                            ->label('Código')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                if (($get('slug') ?? '') !== Str::slug($old)) {
                                    return;
                                }

                                $set('slug', Str::slug($state));
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->label(__('Slug'))
                            ->required()
                            ->maxLength(255)
                            ->unique(
                                table: Process::class,
                                column: 'slug',
                                ignoreRecord: true,
                                modifyRuleUsing: fn(Unique $rule): Unique => $rule->where(
                                    'team_id',
                                    Filament::getTenant()?->id
                                )
                            ),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Descripción')
                    ->schema([
                        Forms\Components\MarkdownEditor::make('description')
                            ->label('Descripción')
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'bulletList',
                                'orderedList',
                                'link',
                            ]),
                    ]),
            ]);
        Forms\Components\Section::make('Entradas y Proveedores')
            ->schema([
                Forms\Components\Repeater::make('suppliers')
                    ->label('Proveedores')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre del Proveedor')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->maxLength(500)
                            ->rows(2),
                    ])
                    ->columns(2)
                    ->defaultItems(1)
                    ->orderable()
                    ->reorderableLabel(false)
                    ->addableLabel('Agregar Proveedor')
                    ->deletableLabel('Eliminar Proveedor'),

                Forms\Components\Repeater::make('inputs')
                    ->label('Insumos/Entradas')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre del Insumo')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->label('Tipo')
                            ->options([
                                'material' => 'Material',
                                'medicamento' => 'Medicamento',
                                'equipo' => 'Equipo',
                                'servicio' => 'Servicio',
                            ])
                            ->default('material'),
                    ])
                    ->columns(2)
                    ->defaultItems(1)
                    ->reorderableLabel()
                    ->addableLabel('Agregar Insumo')
                    ->deletableLabel('Eliminar Insumo'),
            ])
            ->columns(1);

        Forms\Components\Section::make('Procedimientos y Registros')
            ->schema([
                Forms\Components\Repeater::make('procedures')
                    ->label('Procedimientos')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre del Procedimiento')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('frequency')
                            ->label('Frecuencia')
                            ->options([
                                'diaria' => 'Diaria',
                                'semanal' => 'Semanal',
                                'mensual' => 'Mensual',
                                'trimestral' => 'Trimestral',
                            ])
                            ->default('mensual'),
                    ])
                    ->columns(2)
                    ->defaultItems(2)
                    ->reorderableLabel()
                    ->addableLabel('Agregar Procedimiento')
                    ->deletableLabel('Eliminar Procedimiento'),

                Forms\Components\Repeater::make('records')
                    ->label('Registros/Documentos')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre del Registro')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('format')
                            ->label('Formato')
                            ->options([
                                'digital' => 'Digital (PDF)',
                                'papel' => 'Papel',
                            ])
                            ->default('digital'),
                        Forms\Components\Toggle::make('mandatory')
                            ->label('Obligatorio'),
                    ])
                    ->columns(2)
                    ->defaultItems(2)
                    ->reorderableLabel()
                    ->addableLabel('Agregar Registro')
                    ->deletableLabel('Eliminar Registro'),
            ])
            ->columns(1);

        Forms\Components\Section::make('Salidas y Clientes')
            ->schema([
                Forms\Components\Repeater::make('outputs')
                    ->label('Salidas/Productos')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre de la Salida')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('quality_indicator')
                            ->label('Indicador de Calidad')
                            ->placeholder('Superficies limpias y desinfectadas...')
                            ->maxLength(500)
                            ->rows(2),
                    ])
                    ->columns(2)
                    ->defaultItems(1)
                    ->reorderableLabel()
                    ->addableLabel('Agregar Salida')
                    ->deletableLabel('Eliminar Salida'),

                Forms\Components\Repeater::make('clients')
                    ->label('Clientes/Usuarios')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre del Cliente/Usuario')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('role')
                            ->label('Rol')
                            ->options([
                                'interno' => 'Personal Interno',
                                'externo' => 'Cliente Externo',
                                'proveedor' => 'Proveedor',
                                'autoridad' => 'Autoridad Sanitaria',
                            ])
                            ->default('interno'),
                    ])
                    ->columns(2)
                    ->defaultItems(2)
                    ->reorderableLabel()
                    ->addableLabel('Agregar Cliente')
                    ->deletableLabel('Eliminar Cliente'),
            ])
            ->columns(1);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('process_type.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('Pdf')
                        ->icon('phosphor-eye')
                        ->url(function (Process $record) {
                            $tenant = Filament::getTenant();
                            $url = route('generate.characterization', ['tenant' => $tenant?->id, 'process' => $record]);

                            return $url;
                        })
                        ->openUrlInNewTab(),
                ])
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        $tenant = Filament::getTenant();

        return route('generate.characterization', [
            'tenant' => $tenant?->id,
            'process' => $record,
        ]);
    }

    public static function getGlobalSearchResultActions(Model $record): array
    {
        $tenant = Filament::getTenant();

        return [
            Action::make('pdf')
                ->label('PDF')
                ->url(route('generate.characterization', [
                    'tenant' => $tenant?->id,
                    'process' => $record,
                ]), shouldOpenInNewTab: true),
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProcesses::route('/'),
            'create' => Pages\CreateProcess::route('/create'),
            'edit' => Pages\EditProcess::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
