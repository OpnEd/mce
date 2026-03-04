<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MinutesIvcSectionEntryResource\Pages;
use App\Filament\Resources\MinutesIvcSectionEntryResource\RelationManagers;
use App\Models\MinutesIvcSectionEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MinutesIvcSectionEntryResource extends Resource
{
    protected static ?string $model = MinutesIvcSectionEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('minutes_ivc_section_id')
                    ->relationship('minutesIvcSection', 'name')
                    ->required(),
                Forms\Components\Toggle::make('apply')
                    ->required(),
                Forms\Components\Select::make('entry_type')
                    ->options(self::entryTypeOptions())
                    ->native(false)
                    ->required(),
                Forms\Components\TextInput::make('criticality')
                    ->required(),
                Forms\Components\Textarea::make('question')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('answer')
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('links')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label('Clave')
                            ->required(),
                        Forms\Components\TextInput::make('value')
                            ->label('Valor')
                            ->required(),
                    ])
                    ->columns(2)
                    ->default([])
                    ->addActionLabel('Agregar enlace')
                    ->reorderable(false)
                    ->afterStateHydrated(function (Forms\Components\Repeater $component, mixed $state): void {
                        $component->state(MinutesIvcSectionEntry::normalizeLinksForFormState($state));
                    })
                    ->dehydrateStateUsing(fn (mixed $state): array => MinutesIvcSectionEntry::normalizeLinksForStorage($state))
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('compliance')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('minutesIvcSection.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('apply')
                    ->boolean(),
                Tables\Columns\TextColumn::make('entry_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('entry_type'),
                Tables\Columns\TextColumn::make('criticality'),
                Tables\Columns\IconColumn::make('compliance')
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMinutesIvcSectionEntries::route('/'),
            'create' => Pages\CreateMinutesIvcSectionEntry::route('/create'),
            'edit' => Pages\EditMinutesIvcSectionEntry::route('/{record}/edit'),
        ];
    }

    protected static function entryTypeOptions(): array
    {
        return collect(MinutesIvcSectionEntry::values())
            ->mapWithKeys(fn (string $value): array => [$value => strtoupper($value)])
            ->all();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
