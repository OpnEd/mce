<?php

namespace App\Filament\Resources\Quality\Training;

use App\Filament\Resources\Quality\Training\LessonResource\Pages;
use App\Models\Quality\Training\Lesson;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
        return static::buildLessonForm($form);
    }

    public static function table(Table $table): Table
    {
        return static::buildLessonTable($table);
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('module.course', fn (Builder $query) => $query->ownedByTeam(Filament::getTenant()?->id))
            ->with(['module.course']);
    }
}
