<?php

namespace App\Filament\Resources\Quality;

use App\Filament\Resources\Quality\ResiduoResource\Pages;
use App\Models\Residuo;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ResiduoResource extends Resource
{
    protected static ?string $model = Residuo::class;
    protected static ?string $navigationLabel = 'Residuos (RH1)';
    protected static ?string $navigationIcon = 'phosphor-recycle';
    protected static ?string $slug = 'residuos-old';
    protected static ?string $navigationGroup = 'Registros Diarios';
    protected static ?string $recordTitleAttribute = 'reciclable';
    protected static ?string $tenantOwnershipRelationshipName = 'team';
    protected static ?string $tenantRelationshipName = 'residuos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('reciclable')
                    ->label('Reciclables (B. Blanca)')
                    ->numeric()
                    ->inputMode('decimal')
                    ->required()
                    ->default(0)
                    ->suffix('Kg'),
                TextInput::make('ordinario')
                    ->label('Ordinarios (B. Negra)')
                    ->numeric()
                    ->inputMode('decimal')
                    ->required()
                    ->default(0)
                    ->suffix('Kg'),
                TextInput::make('guardian')
                    ->label('Biosanitarios (B. Roja)')
                    ->numeric()
                    ->inputMode('decimal')
                    ->required()
                    ->default(0)
                    ->suffix('Kg'),
                TextInput::make('bolsa_roja')
                    ->label('Cortopunzantes (Guardián)')
                    ->numeric()
                    ->inputMode('decimal')
                    ->required()
                    ->default(0)
                    ->suffix('Kg'),
                FileUpload::make('imagen')
                    ->label('Constancia de Recolección (máx. 1024kb)*')
                    ->image()
                    ->maxSize(1024)
                    ->deletable()
                    ->openable()
                    ->downloadable()
                    ->getUploadedFileNameForStorageUsing(
                        fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                            ->prepend('recoleccionResiduos-'),
                    )
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->date('d-m-Y'),
                TextColumn::make('reciclable')
                    ->label('Reciclables')
                    ->summarize(Average::make())
                    ->summarize(Sum::make()),
                TextColumn::make('ordinario')
                    ->label('Ordinarios')
                    ->summarize(Average::make())
                    ->summarize(Sum::make()),
                TextColumn::make('guardian')
                    ->label('Cortopunzantes (Guardián)')
                    ->summarize(Average::make())
                    ->summarize(Sum::make()),
                TextColumn::make('bolsa_roja')
                    ->label('Bolsas Rojas')
                    ->summarize(Average::make())
                    ->summarize(Sum::make()),
                TextColumn::make('user.name')
                    ->label('Registrado por()')
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('desde'),
                        DatePicker::make('hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['desde'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['hasta'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('user.name')
                    ->label('Registró'),
                TextEntry::make('reciclable')
                    ->label('Peso de residuos reciclables (kg)*')
                    ->weight(FontWeight::Bold),
                TextEntry::make('ordinario')
                    ->label('Peso de residuos ordinarios (kg)*')
                    ->weight(FontWeight::Bold),
                TextEntry::make('guardian')
                    ->label('Peso de cortopunzantes (Guardián) (kg)*')
                    ->weight(FontWeight::Bold),
                TextEntry::make('bolsa_roja')
                    ->label('Peso de residuos infecciosos (kg)*')
                    ->weight(FontWeight::Bold),
                ImageEntry::make('imagen')
                    ->label('Constancia de recolección'),
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
            'index' => Pages\ListResiduos::route('/'),
            'create' => Pages\CreateResiduo::route('/create'),
            'edit' => Pages\EditResiduo::route('/{record}/edit'),
        ];
    }
}
