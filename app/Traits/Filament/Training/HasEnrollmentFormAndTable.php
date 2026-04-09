<?php

namespace App\Traits\Filament\Training;

use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

trait HasEnrollmentFormAndTable
{
    public static function buildEnrollmentForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('team_id')
                    ->default(fn () => Filament::getTenant()?->id)
                    ->hidden(),
                Forms\Components\Select::make('user_id')
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query->whereHas(
                            'teams',
                            fn (Builder $teamQuery) => $teamQuery->whereKey(Filament::getTenant()?->id)
                        )
                    )
                    ->required(),
                Forms\Components\Select::make('course_id')
                    ->relationship(
                        name: 'course',
                        titleAttribute: 'title',
                        modifyQueryUsing: fn (Builder $query) => $query
                            ->active()
                            ->visibleToTeam(Filament::getTenant()?->id)
                    )
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('in_progress'),
                Forms\Components\TextInput::make('progress')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\DateTimePicker::make('started_at'),
                Forms\Components\DateTimePicker::make('completed_at'),
                Forms\Components\DateTimePicker::make('last_accessed_at'),
                Forms\Components\DateTimePicker::make('certificated_at'),
                Forms\Components\TextInput::make('certificate_url')
                    ->maxLength(255),
                Forms\Components\TextInput::make('score_final')
                    ->numeric(),
            ]);
    }

    public static function buildEnrollmentTable(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('progress')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('started_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_accessed_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('certificated_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('certificate_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('score_final')
                    ->numeric()
                    ->sortable(),
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
                //
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->label('Exportar CSV')
                    ->exporter(\App\Filament\Exporters\EnrollmentExporter::class),
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
