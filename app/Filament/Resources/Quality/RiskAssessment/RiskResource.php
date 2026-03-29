<?php

namespace App\Filament\Resources\Quality\RiskAssessment;

use App\Filament\Resources\Quality\RiskAssessment\RiskResource\Pages;
use App\Models\Process;
use App\Models\Quality\RiskAssessment\Risk;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RiskResource extends Resource
{
    protected static ?string $model = Risk::class;

    protected static ?int $navigationSort = 16;
    protected static ?string $navigationGroup = 'Plataforma Estratégica';
    protected static ?string $navigationLabel = 'Matriz de riesgos';
    protected static ?string $pluralModelLabel = 'Matriz de riesgos';
    protected static ?string $modelLabel = 'Riesgo';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $slug = 'matriz-de-riesgos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Contexto')
                    ->schema([
                        Forms\Components\Select::make('process_id')
                            ->label('Proceso')
                            ->relationship('process', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('code')
                            ->label('Codigo')
                            ->maxLength(50)
                            ->placeholder('RISK-001'),
                        Forms\Components\TextInput::make('activity')
                            ->label('Actividad / Subproceso')
                            ->maxLength(255),
                        Forms\Components\Select::make('owner_id')
                            ->label('Responsable')
                            ->relationship('owner', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('title')
                            ->label('Riesgo')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Descripcion del riesgo')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Descripcion')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('cause')
                            ->label('Causa')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('consequence')
                            ->label('Consecuencia')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
                Section::make('Clasificacion')
                    ->schema([
                        Forms\Components\Select::make('risk_type')
                            ->label('Tipo de riesgo')
                            ->options([
                                'operacional' => 'Operacional',
                                'sanitario' => 'Sanitario',
                                'regulatorio' => 'Regulatorio',
                                'calidad' => 'Calidad',
                                'seguridad' => 'Seguridad del paciente',
                                'logistico' => 'Logistico',
                                'tecnologico' => 'Tecnologico',
                                'financiero' => 'Financiero',
                            ]),
                        Forms\Components\Select::make('impact_area')
                            ->label('Area de impacto')
                            ->options([
                                'paciente' => 'Paciente',
                                'producto' => 'Producto',
                                'regulatorio' => 'Regulatorio',
                                'operativo' => 'Operativo',
                                'financiero' => 'Financiero',
                                'infraestructura' => 'Infraestructura',
                                'ambiente' => 'Ambiente',
                            ]),
                        Forms\Components\Textarea::make('existing_controls')
                            ->label('Controles existentes')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Evaluacion inicial')
                    ->schema([
                        Forms\Components\Select::make('probability')
                            ->label('Probabilidad')
                            ->options(self::probabilityOptions())
                            ->required(),
                        Forms\Components\Select::make('impact')
                            ->label('Impacto')
                            ->options(self::impactOptions())
                            ->required(),
                        Placeholder::make('risk_score_display')
                            ->label('Nivel de riesgo (P x I)')
                            ->content(function (Forms\Get $get): string {
                                $prob = (int) $get('probability');
                                $impact = (int) $get('impact');
                                if ($prob <= 0 || $impact <= 0) {
                                    return '-';
                                }
                                $score = $prob * $impact;
                                $level = (new Risk())->riskLevelLabel((new Risk())->levelFromScore($score));
                                return "{$score} ({$level})";
                            }),
                    ])
                    ->columns(3),
                Section::make('Riesgo residual')
                    ->schema([
                        Forms\Components\Select::make('residual_probability')
                            ->label('Probabilidad residual')
                            ->options(self::probabilityOptions()),
                        Forms\Components\Select::make('residual_impact')
                            ->label('Impacto residual')
                            ->options(self::impactOptions()),
                        Placeholder::make('residual_score_display')
                            ->label('Nivel residual (P x I)')
                            ->content(function (Forms\Get $get): string {
                                $prob = (int) $get('residual_probability');
                                $impact = (int) $get('residual_impact');
                                if ($prob <= 0 || $impact <= 0) {
                                    return '-';
                                }
                                $score = $prob * $impact;
                                $level = (new Risk())->riskLevelLabel((new Risk())->levelFromScore($score));
                                return "{$score} ({$level})";
                            }),
                    ])
                    ->columns(3),
                Section::make('Tratamiento y seguimiento')
                    ->schema([
                        Forms\Components\Textarea::make('treatment_plan')
                            ->label('Plan de tratamiento / Acciones')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                'abierto' => 'Abierto',
                                'en_tratamiento' => 'En tratamiento',
                                'aceptado' => 'Aceptado',
                                'cerrado' => 'Cerrado',
                            ])
                            ->default('abierto')
                            ->required(),
                        Forms\Components\DatePicker::make('review_at')
                            ->label('Fecha de revision'),
                    ])
                    ->columns(2),
                Section::make('Datos adicionales')
                    ->schema([
                        KeyValue::make('data')
                            ->label('Datos adicionales')
                            ->keyPlaceholder('Clave')
                            ->valuePlaceholder('Valor')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Codigo')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('process.name')
                    ->label('Proceso')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Riesgo')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('risk_score')
                    ->label('P x I')
                    ->sortable(),
                Tables\Columns\TextColumn::make('risk_level')
                    ->label('Nivel')
                    ->badge()
                    ->formatStateUsing(fn (?string $state, Risk $record): string => $record->riskLevelLabel($state))
                    ->color(fn (?string $state, Risk $record): string => $record->riskLevelColor($state)),
                Tables\Columns\TextColumn::make('residual_level')
                    ->label('Residual')
                    ->badge()
                    ->formatStateUsing(fn (?string $state, Risk $record): string => $record->riskLevelLabel($state))
                    ->color(fn (?string $state, Risk $record): string => $record->riskLevelColor($state))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'abierto' => 'danger',
                        'en_tratamiento' => 'warning',
                        'aceptado' => 'info',
                        'cerrado' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('review_at')
                    ->label('Revision')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Responsable')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('process_id')
                    ->label('Proceso')
                    ->options(function () {
                        $tenantId = Filament::getTenant()?->id;
                        return Process::query()
                            ->when($tenantId, fn ($query) => $query->where('team_id', $tenantId))
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->all();
                    }),
                Tables\Filters\SelectFilter::make('risk_level')
                    ->label('Nivel')
                    ->options([
                        'bajo' => 'Bajo',
                        'medio' => 'Medio',
                        'alto' => 'Alto',
                        'critico' => 'Critico',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'abierto' => 'Abierto',
                        'en_tratamiento' => 'En tratamiento',
                        'aceptado' => 'Aceptado',
                        'cerrado' => 'Cerrado',
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                ]),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRisks::route('/'),
            'create' => Pages\CreateRisk::route('/create'),
            'matrix' => Pages\RiskMatrix::route('/matriz'),
            'view' => Pages\ViewRisk::route('/{record}'),
            'edit' => Pages\EditRisk::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    private static function probabilityOptions(): array
    {
        return [
            1 => '1 - Muy baja',
            2 => '2 - Baja',
            3 => '3 - Media',
            4 => '4 - Alta',
            5 => '5 - Muy alta',
        ];
    }

    private static function impactOptions(): array
    {
        return [
            1 => '1 - Menor',
            2 => '2 - Moderado',
            3 => '3 - Significativo',
            4 => '4 - Mayor',
            5 => '5 - Critico',
        ];
    }
}
