<?php

namespace App\Filament\Resources\MinutesIvcSectionResource\RelationManagers;

use App\Models\MinutesIvcSectionEntry;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'entries';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                /*  Section::make('Detalles de Entrada')
                    ->schema([
                        Forms\Components\Select::make('minutes_ivc_section_id')
                            ->relationship('minutesIvcSection', 'name')
                            ->required()
                            ->disabled()
                            ->hidden(),
                        Forms\Components\Toggle::make('apply')
                            ->required()
                            ->disabled()
                            ->hidden(),
                        Forms\Components\TextInput::make('entry_type')
                            ->required()
                            ->disabled()
                            ->hidden(),
                        Forms\Components\TextInput::make('criticality')
                            ->required()
                            ->disabled()
                            ->hidden()
                    ])
                    ->collapsed(), */
                Forms\Components\Textarea::make('question')
                    ->label('Dato requerido')
                    ->required()
                    ->columnSpanFull()
                    ->disabled(),
                Forms\Components\Textarea::make('answer')
                    ->label('Respuesta')
                    ->columnSpanFull(),
                Forms\Components\Placeholder::make('links_notice')
                    ->label('Ubicación de archivos / enlaces')
                    ->content('Esta entrada no tiene archivos / enlaces.')
                    ->visible(fn (Forms\Get $get): bool => empty(MinutesIvcSectionEntry::normalizeLinksForFormState($get('links'))))
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('links')
                    ->label('Ubicación de archivos / enlaces')
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
                    ->disabled()
                    ->visible(fn (Forms\Get $get): bool => ! empty(MinutesIvcSectionEntry::normalizeLinksForFormState($get('links'))))
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('compliance')
                    ->label('¿Cumple?')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Puntos de Sección de Acta')
            ->columns([
                Tables\Columns\TextColumn::make('entry_id')
                    ->label('Ítem')
                    ->searchable(),
                Tables\Columns\TextColumn::make('question')
                    ->label('Requerimiento')
                    ->searchable(),
                Tables\Columns\IconColumn::make('apply')
                    ->label('¿Aplica?')
                    ->boolean(),
                Tables\Columns\TextColumn::make('criticality')
                    ->label('Criticidad')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'menor' => 'info',
                        'Mayor' => 'warning',
                        'Crítico' => 'danger',
                    }),
                Tables\Columns\IconColumn::make('compliance')
                    ->label('Cumplimiento')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado en')
                    ->dateTime('dd-mm-yyyy')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado en')
                    ->dateTime('dd-mm-yyyy')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Responder')
                    ->modalHeading('Responder al siguiente requerimiento'),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }
}
