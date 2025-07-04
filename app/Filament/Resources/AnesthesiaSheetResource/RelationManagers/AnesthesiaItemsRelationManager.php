<?php

namespace App\Filament\Resources\AnesthesiaSheetResource\RelationManagers;

use App\Models\Inventory;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;

class AnesthesiaItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'anesthesiaItems';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('phase')
                    ->options([
                        'pre_anesthesia' => 'Pre Anesthesia',
                        'intraoperative' => 'Intraoperative',
                        'post_anesthesia' => 'Post Anesthesia',
                    ])
                    ->default('pre_anesthesia')
                    ->required(),
                Forms\Components\Select::make('inventory_id')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->relationship(
                        'inventory',           // nombre de la relación en el modelo AnesthesiaSheetItem
                        'product_name',                  // la columna "display" (la reemplazamos después con ->getOptionLabel)
                        function ($query) {
                            $query
                                ->whereHas('product', fn($q) => $q->where('is_mce', true))
                                ->with('product');  // cargamos product para el label
                        }
                    )
                    ->afterStateUpdated(function (?string $state, Set $set, Get $get) {

                        // Get related values

                        $record = $this->getOwnerRecord();
                        $inventory = Inventory::find($state);
                        $petWeight = optional(optional($record)->pet)->weight ?? 0;
                        $drugConcentration = optional(optional($inventory)->product)->drug_concentration ?? 1;
                        $dosePerKg = optional(optional($inventory)->product)->recommended_dose ?? 1;

                        if ($petWeight && $drugConcentration && $dosePerKg) {
                            $result = ($dosePerKg * $petWeight) / $drugConcentration;
                            $set('dose_per_kg', $dosePerKg);
                            $set('dose_measure', $result);
                            $set('dose_measure_unit', optional($inventory)->product->pharmacheutical_form === 'Tableta' ? 'tab' : 'ml');
                        }
                    })
                    ->live(),
                Forms\Components\TextInput::make('dose_per_kg')
                    ->default(0)
                    ->required()
                    ->readonly(),
                Forms\Components\TextInput::make('dose_measure')
                    ->default(0)
                    ->required(),
                Forms\Components\TextInput::make('dose_measure_unit')
                    ->placeholder('***')
                    ->readOnly()
                    ->required()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('inventory_id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('phase')
                    ->label(__('Phase')),
                Tables\Columns\TextColumn::make('inventory.product_name')
                    ->label(__('Drug'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dose_per_kg')
                    ->label(__('Dose (mg/kg)'))
                    ->sortable()
                    ->numeric(),
                Tables\Columns\TextInputColumn::make('dose_measure')
                    ->label(__('Measurement')),
                Tables\Columns\TextColumn::make('dose_measure_unit')
                    ->label(__('Measurement Units')),
                Tables\Columns\TextColumn::make('total_dose_mg')
                    ->label('Total Dose (mg)')
                    ->getStateUsing(function ($record) {
                        $concentration = optional(optional($record->inventory)->product)->drug_concentration ?? 0;
                        $doseMeasure = $record->dose_measure ?? 0;
                        return $concentration * $doseMeasure;
                    })
                    ->formatStateUsing(fn($state) => number_format($state, 2)),
                Tables\Columns\TextColumn::make('administration_route')
                    ->label(__('Administration Route')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
