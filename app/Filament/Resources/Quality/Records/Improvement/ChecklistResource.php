<?php

namespace App\Filament\Resources\Quality\Records\Improvement;

use App\Filament\Resources\Quality\Records\Improvement\ChecklistResource\Pages;
use App\Filament\Resources\Quality\Records\Improvement\ChecklistResource\RelationManagers;
use App\Models\Quality\Records\Improvement\Checklist;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChecklistResource extends Resource
{
    protected static ?string $model = Checklist::class;

    protected static ?int $navigationSort = 15;
    protected static ?string $navigationGroup = 'Plataforma Estratégica';
    protected static ?string $navigationLabel = 'Planes de auditoria';
    protected static ?string $pluralModelLabel = 'Planes de auditoria';
    protected static ?string $modelLabel = 'Plan de auditoria';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $slug = 'plan-de-auditoria';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informacion general')
                    ->schema([
                        Forms\Components\Select::make('process_id')
                            ->label('Proceso')
                            ->relationship('process', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->label('Titulo del plan')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Objetivo y descripcion')
                    ->schema([
                        Forms\Components\Textarea::make('objective')
                            ->label('Objetivo')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->label('Descripcion')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
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
                Tables\Columns\TextColumn::make('title')
                    ->label('Plan')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('process.name')
                    ->label('Proceso')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('team.name')
                    ->label('Equipo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('checklist_items_count')
                    ->label('Items')
                    ->counts('checklistItems')
                    ->badge(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Eliminado en')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado en')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado en')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\ChecklistItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChecklists::route('/'),
            'create' => Pages\CreateChecklist::route('/create'),
            'view' => Pages\ViewChecklist::route('/{record}'),
            'edit' => Pages\EditChecklist::route('/{record}/edit'),
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

