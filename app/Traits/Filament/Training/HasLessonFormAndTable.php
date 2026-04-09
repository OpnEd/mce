<?php

namespace App\Traits\Filament\Training;

use App\Models\Quality\Training\Lesson;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

trait HasLessonFormAndTable
{
    public static function buildLessonForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información General')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('module_id')
                            ->label('Módulo')
                            ->relationship(
                                name: 'module',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn (Builder $query) => $query->whereHas(
                                    'course',
                                    fn (Builder $courseQuery) => $courseQuery->ownedByTeam(Filament::getTenant()?->id)
                                )
                            )
                            ->required(),
                        Forms\Components\TextInput::make('duration')
                            ->label('Duración (minutos)')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('order')
                            ->label('Orden')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\Select::make('completion_mode')
                            ->label('Modo de cierre')
                            ->options([
                                Lesson::COMPLETION_MODE_CONSUMPTION_ONLY => 'Solo consumo',
                                Lesson::COMPLETION_MODE_ASSESSMENT_REQUIRED => 'Requiere evaluación',
                            ])
                            ->default(Lesson::COMPLETION_MODE_ASSESSMENT_REQUIRED)
                            ->required(),
                        Forms\Components\Toggle::make('active')
                            ->label('Activa')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Contenido Didáctico')
                    ->schema([
                        Forms\Components\RichEditor::make('introduction')
                            ->label('Introducción')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->label('Descripción corta')
                            ->columnSpanFull(),
                        Forms\Components\Repeater::make('objectives')
                            ->label('Objetivos de aprendizaje')
                            ->simple(
                                Forms\Components\TextInput::make('objective')->required()
                            )
                            ->columnSpanFull(),
                        Forms\Components\Repeater::make('content')
                            ->label('Bloques de contenido')
                            ->simple(
                                Forms\Components\RichEditor::make('content_item')->required()
                            )
                            ->columnSpanFull(),
                        Forms\Components\Repeater::make('conclusions')
                            ->label('Conclusiones')
                            ->simple(
                                Forms\Components\TextInput::make('conclusion')->required()
                            )
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Multimedia y Referencias')
                    ->schema([
                        Forms\Components\FileUpload::make('ilustrations')
                            ->label('Ilustraciones / Imágenes')
                            ->multiple()
                            ->image()
                            ->directory('lessons/ilustrations'),
                        Forms\Components\TextInput::make('video_url')
                            ->label('Video URL')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('iframe')
                            ->label('Código Iframe')
                            ->maxLength(2000),
                        Forms\Components\Repeater::make('references')
                            ->label('Referencias y Bibliografía')
                            ->schema([
                                Forms\Components\TextInput::make('text')->label('Texto/Cita')->required(),
                                Forms\Components\TextInput::make('url')->label('Enlace (opcional)')->url(),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function buildLessonTable(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('module.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completion_mode')
                    ->label('Modo de cierre')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        Lesson::COMPLETION_MODE_CONSUMPTION_ONLY => 'Solo consumo',
                        Lesson::COMPLETION_MODE_ASSESSMENT_REQUIRED => 'Requiere evaluación',
                        default => $state ?? '-',
                    })
                    ->badge(),
                Tables\Columns\TextColumn::make('video_url')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
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
                SelectFilter::make('module')
                    ->relationship('module', 'title')
                    ->searchable()
                    ->preload()
                    ->label('Módulo'),
                TernaryFilter::make('active')
                    ->label('Activa')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
