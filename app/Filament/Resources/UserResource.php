<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Settings;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    //protected static ?string $cluster = Settings::class;
    protected static ?string $tenantOwnershipRelationshipName = 'teams';
    //protected static ?string $navigationIcon = 'phosphor-user';

    protected static ?string $navigationGroup = 'Configuración de plataforma';
    protected static ?string $pluralModelLabel = 'Usuarios';
    protected static ?string $modelLabel = 'Usuario';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('fields.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label(__('fields.email'))
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->label(__('fields.password'))
                    ->password()
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->maxLength(255),
                Forms\Components\Select::make('card_type')
                    ->label(__('fields.card_type'))
                    ->options([
                        'Cédula de Ciudadanía' => 'C.C.',
                        'Cédula de Extranjería' => 'C.E.',
                        'Pasaporte' => 'Pasaporte',
                        'Permiso Especial de Permanencia' => 'P.E.P.',
                    ]),
                Forms\Components\TextInput::make('card_number')
                    ->label(__('fields.card_number'))
                    ->maxLength(50),
                Forms\Components\Toggle::make('is_suspended')
                    ->label(__('fields.is_suspended'))
                    ->inline(false)
                    ->default(false),
                Forms\Components\KeyValue::make('data')
                    ->label(__('fields.extra_data'))
                    ->keyPlaceholder('Address:')
                    ->valuePlaceholder('Calle 123 #45-67')
                    ->columnSpanFull(),
                SignaturePad::make('signature')
                    ->label(__('fields.signature'))
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('profile_photo_path')
                    ->label(__('fields.profile_photo'))
                    ->image()
                    ->disk('public')
                    ->directory('profile-photos')
                    ->maxSize(2048)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
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
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\RolesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
