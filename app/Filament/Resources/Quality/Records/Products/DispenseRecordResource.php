<?php

namespace App\Filament\Resources\Quality\Records\Products;

use App\Jobs\SendMedicationRationalUseEmailJob;

use App\Filament\Resources\Quality\Records\Products\DispenseRecordResource\Pages;
use App\Models\Quality\Records\Products\DispenseRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DispenseRecordResource extends Resource
{
    protected static ?string $model = DispenseRecord::class;

    protected static ?string $navigationGroup = 'Registros Diarios';
    protected static ?string $slug = 'promocion-uso-racional-medicamentos';
    protected static ?string $pluralModelLabel = 'Promoción Uso Racional de Medicamentos';
    protected static ?string $modelLabel = 'Promoción Uso Racional de Medicamentos';

    public static function form(Form $form): Form
    {
        $options = collect(config('medications'))
            ->mapWithKeys(fn($item, $key) => [$key => $item['name']])
            ->toArray();

        return $form
            ->schema([
                Forms\Components\TextInput::make('client_name')
                ->label('Nombre del usuario'),
            Forms\Components\TextInput::make('client_email')
                ->email()
                ->required()
                ->label('Correo electrónico'),
            Forms\Components\TextInput::make('client_phone')
                ->tel()
                ->label('Teléfono'),
            Forms\Components\Select::make('medication_key')
                ->label('Medicamento dispensado')
                ->options($options)
                ->required(),
                //->live()
                //->afterStateUpdated(fn($state, callable $set) =>
                //    $set('medication_name', config("medications.$state.name"))
                //),
            Forms\Components\TextInput::make('notes'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_name')->label('Usuario'),
                Tables\Columns\TextColumn::make('user_email')->label('Email'),
                Tables\Columns\TextColumn::make('medication_name')->label('Medicamento'),
                Tables\Columns\TextColumn::make('sent_at')->label('Fecha envío')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('resend_email')
                    ->label('Reenviar Correo')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->action(function (DispenseRecord $record) {
                        SendMedicationRationalUseEmailJob::dispatch($record);
                        \Filament\Notifications\Notification::make()
                            ->title('Correo reenviado')
                            ->success()
                            ->send();
                    }),
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
            'index' => Pages\ListDispenseRecords::route('/'),
            'create' => Pages\CreateDispenseRecord::route('/create'),
            'edit' => Pages\EditDispenseRecord::route('/{record}/edit'),
        ];
    }
}
