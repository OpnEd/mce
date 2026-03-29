<?php

namespace App\Filament\Resources\Quality\Training;

use App\Filament\Resources\Quality\Training\ModuleResource\Pages;
use App\Filament\Resources\Quality\Training\ModuleResource\RelationManagers;
use App\Models\Quality\Training\Module;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;

    protected static ?string $navigationLabel = 'Módulos';

    protected static ?string $modelLabel = 'Módulo';

    protected static ?string $pluralModelLabel = 'Módulos';

    protected static ?int $navigationSort = 20;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información General')
                    ->schema([
                        Forms\Components\Select::make('course_id')
                            ->label('Curso')
                            ->relationship('course', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('title')
                            ->label('Título del Módulo')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('objective')
                            ->label('Objetivo')
                            ->rows(2)
                            ->maxLength(500),

                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Detalles')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->label('Orden dentro del Curso')
                            ->numeric()
                            ->minValue(1)
                            ->required(),

                        Forms\Components\TextInput::make('duration')
                            ->label('Duración (minutos)')
                            ->numeric()
                            ->minValue(1)
                            ->required(),

                        Forms\Components\FileUpload::make('image')
                            ->label('Imagen del Módulo')
                            ->image()
                            ->disk('public')
                            ->directory('module_images')
                            ->maxSize(5120),
                    ])->columns(2),

                Forms\Components\Section::make('Estado')
                    ->schema([
                        Forms\Components\Toggle::make('active')
                            ->label('Activo')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('Orden')
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('course.title')
                    ->label('Curso')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration')
                    ->label('Duración')
                    ->formatStateUsing(fn ($state) => $state ? gmdate('H:i', $state * 60) : '-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('lessons_count')
                    ->counts('lessons')
                    ->label('Lecciones'),

                Tables\Columns\IconColumn::make('active')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course_id')
                    ->label('Curso')
                    ->relationship('course', 'title')
                    ->searchable(),

                Tables\Filters\TernaryFilter::make('active')
                    ->label('Activo'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('course_id', 'order');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LessonsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModules::route('/'),
            'create' => Pages\CreateModule::route('/create'),
            'view' => Pages\ViewModule::route('/{record}'),
            'edit' => Pages\EditModule::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['course'])
            ->withCount('lessons');
    }
}
