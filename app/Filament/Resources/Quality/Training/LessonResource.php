<?php

namespace App\Filament\Resources\Quality\Training;

use App\Filament\Resources\Quality\Training\LessonResource\Pages;
use App\Filament\Resources\Quality\Training\LessonResource\RelationManagers;
use App\Models\Quality\Training\Lesson;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static bool $isScopedToTenant = false;

    protected static ?string $navigationLabel = 'Lecciones';

    protected static ?string $modelLabel = 'Lección';

    protected static ?string $pluralModelLabel = 'Lecciones';

    protected static ?int $navigationSort = 30;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('objective')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('duration')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('module_id')
                    ->relationship('module', 'title')
                    ->required(),
                Forms\Components\TextInput::make('order')
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
                Forms\Components\Textarea::make('content')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('video_url')
                    ->label('Video URL')
                    ->maxLength(255),
                Forms\Components\TextInput::make('iframe')
                    ->label('Iframe/Embedded Code')
                    ->maxLength(2000)
                    ->helperText('Código iframe para embeber videos o contenido externo'),
                Forms\Components\Toggle::make('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
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
                    ->searchable(),
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
                    ->boolean()
                    ->trueLabel('Sí')
                    ->falseLabel('No')
                    ->native(false),
                SelectFilter::make('completion_mode')
                    ->label('Modo de cierre')
                    ->options([
                        Lesson::COMPLETION_MODE_CONSUMPTION_ONLY => 'Solo consumo',
                        Lesson::COMPLETION_MODE_ASSESSMENT_REQUIRED => 'Requiere evaluaciÃ³n',
                    ]),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Creado desde'),
                        Forms\Components\DatePicker::make('created_until')->label('Creado hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date));
                    }),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
            'view' => Pages\LessonView::route('/{record}'),
        ];
    }
}
