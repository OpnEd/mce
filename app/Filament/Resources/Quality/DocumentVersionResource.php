<?php

namespace App\Filament\Resources\Quality;

use App\Filament\Resources\Quality\DocumentVersionResource\Pages;
use App\Filament\Resources\Quality\DocumentVersionResource\RelationManagers;
use App\Models\Quality\DocumentVersion;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentVersionResource extends Resource
{
    protected static ?string $model = DocumentVersion::class;

    protected static ?int $navigationSort = 16;
    protected static ?string $navigationGroup = 'Plataforma Estratégica';
    protected static ?string $pluralModelLabel = 'Versiones de Documentos';
    protected static ?string $modelLabel = 'Versión de documento';
    protected static ?string $slug = 'versiones-de-documentos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('document_id')
                            ->label('Documento actualizado')
                            ->content(fn(?DocumentVersion $record): string => $record?->title ?? '-')
                            ->helperText(str('**Usuario** que elaboró el documento.')->inlineMarkdown()->toHtmlString()),
                TextInput::make('changes'),
                Textarea::make('comment'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
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
            'index' => Pages\ListDocumentVersions::route('/'),
            'create' => Pages\CreateDocumentVersion::route('/create'),
            'edit' => Pages\EditDocumentVersion::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
