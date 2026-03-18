<?php

namespace App\Filament\Resources\Quality;

use App\Filament\Resources\Quality\WasteGenerationReportResource\Pages;
use App\Filament\Resources\WasteGenerationReportResource\RelationManagers;
use App\Models\Quality\WasteGenerationReport;
use App\Services\Quality\WasteGenerationReportService;
use Filament\Facades\Filament;
use Filament\GlobalSearch\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class WasteGenerationReportResource extends Resource
{
    protected static ?string $model = WasteGenerationReport::class;
    
    protected static ?string $modelLabel = 'Informe de residuos';
    protected static ?string $pluralModelLabel = 'Informes de Residuos';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Plataforma Estratégica';
    protected static ?string $slug = 'informe-generacion-residuos';
    protected static ?string $recordTitleAttribute = 'numero_informe';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Informe')
                    ->description('Detalles generales del informe de residuos')
                    ->schema([
                        Forms\Components\TextInput::make('numero_informe')
                            ->label('Número de Informe')
                            ->disabled()
                            ->dehydrated()
                            ->maxLength(50),

                        Forms\Components\Select::make('anio')
                            ->label('Año')
                            ->options(function () {
                                $years = [];
                                for ($i = now()->year; $i >= now()->year - 10; $i--) {
                                    $years[$i] = $i;
                                }
                                return $years;
                            })
                            ->required()
                            ->disabled(fn (?Model $record) => $record !== null),

                        Forms\Components\Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'generado' => 'Generado',
                                'validado' => 'Validado',
                                'archivado' => 'Archivado',
                            ])
                            ->default('generado')
                            ->required(),

                        Forms\Components\Textarea::make('descripcion')
                            ->label('Descripción')
                            ->columnSpanFull()
                            ->rows(3),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Totales de Residuos')
                    ->description('Resumen de residuos por categoría (en kg)')
                    ->schema([
                        Forms\Components\TextInput::make('total_reciclable')
                            ->label('Total Reciclable (kg)')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('total_ordinario')
                            ->label('Total Ordinario (kg)')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('total_guardian')
                            ->label('Total Guardián (kg)')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('total_bolsa_roja')
                            ->label('Total Bolsa Roja (kg)')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('total_general')
                            ->label('Total General (kg)')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Estadísticas')
                    ->description('Datos estadísticos del informe')
                    ->schema([
                        Forms\Components\TextInput::make('cantidad_registros')
                            ->label('Cantidad de Registros')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('fecha_generacion')
                            ->label('Fecha de Generación')
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        $tenant = Filament::getTenant();

        return route('informe.residuos', [
            'tenant' => $tenant?->id,
            'report' => $record,
        ]);
    }

    public static function getGlobalSearchResultActions(Model $record): array
    {
        $tenant = Filament::getTenant();

        return [
            Action::make('pdf')
                ->label('PDF')
                ->url(route('informe.residuos', [
                    'tenant' => $tenant?->id,
                    'report' => $record,
                ]), shouldOpenInNewTab: true),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero_informe')
                    ->label('Número')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-m-document-text')
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('anio')
                    ->label('Año')
                    ->sortable()
                    ->badge()
                    ->numeric(),

                Tables\Columns\TextColumn::make('total_general')
                    ->label('Total (kg)')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn (float $state): string => number_format($state, 2, ',', '.')),

                Tables\Columns\TextColumn::make('cantidad_registros')
                    ->label('Registros')
                    ->numeric()
                    ->sortable()
                    ->alignment('center')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'generado',
                        'success' => 'validado',
                        'info' => 'archivado',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Generado por')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_generacion')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('anio')
                    ->label('Año')
                    ->options(function () {
                        $years = [];
                        for ($i = now()->year; $i >= now()->year - 10; $i--) {
                            $years[$i] = $i;
                        }
                        return $years;
                    }),

                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'generado' => 'Generado',
                        'validado' => 'Validado',
                        'archivado' => 'Archivado',
                    ]),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Ver')
                        ->icon('heroicon-m-eye'),

                    Tables\Actions\EditAction::make()
                        ->label('Editar')
                        ->icon('heroicon-m-pencil'),

                    Tables\Actions\Action::make('descargar_pdf')
                        ->label('Descargar PDF')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->color('success')
                        ->url(function (WasteGenerationReport $record) {
                            $tenant = Filament::getTenant();
                            $url = route('informe.residuos', ['tenant' => $tenant?->id, 'report' => $record]);

                            \Illuminate\Support\Facades\Log::info('DEBUG URL PDF:', [
                                'tenant_obj' => $tenant ? 'found' : 'null',
                                'tenant_id' => $tenant?->id,
                                'report_id' => $record->id,
                                'numero_informe' => $record->numero_informe,
                                'generated_url' => $url,
                            ]);

                            return $url;
                        })
                        ->openUrlInNewTab(),

                    Tables\Actions\DeleteAction::make()
                        ->label('Eliminar')
                        ->icon('heroicon-m-trash'),
                ]),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListWasteGenerationReports::route('/'),
            'create' => Pages\CreateWasteGenerationReport::route('/create'),
            'view' => Pages\ViewWasteGenerationReport::route('/{record}'),
            'edit' => Pages\EditWasteGenerationReport::route('/{record}/edit'),
        ];
    }

    /**
     * Descargar informe en PDF
     */
    public static function descargarPDF(WasteGenerationReport $informe)
    {
        return WasteGenerationReportService::descargarPDF($informe);
    }
}
