<?php

namespace App\Filament\Resources\Quality;

use App\Filament\Resources\Quality\DocumentResource\Pages;
use App\Filament\Resources\Quality\DocumentResource\RelationManagers;
use App\Models\Document;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Forms\Get;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?int $navigationSort = 15;
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Gestión de Documental';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('Title'))
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                        if (($get('slug') ?? '') !== Str::slug($old)) {
                            return;
                        }

                        $set('slug', Str::slug($state));
                    }),
                Forms\Components\Select::make('process_id')
                    ->label(__('Process'))
                    ->helperText(str('Selecciona el **Proceso** al que pertenece el documento. Ejm.: *Gestión de Documentos*')->inlineMarkdown()->toHtmlString())
                    ->relationship('process', 'name')
                    ->required(),
                Forms\Components\Select::make('document_category_id')
                    ->label(__('Document Category'))
                    ->helperText(str('Selecciona la **Categoría de Documento** a la que pertenece el documento. Ejm.: *Procedimiento*')->inlineMarkdown()->toHtmlString())
                    ->relationship('document_category', 'name')
                    ->required(),
                Forms\Components\TextInput::make('slug')
                    ->label(__('Slug'))
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('objective')
                    ->label('1. Objetivo')
                    ->maxLength(65535)
                    ->helperText(str('Escribe acá el **objetivo** del documento. Ejm.: *Establecer los lineamientos para la gestión de documentos*')->inlineMarkdown()->toHtmlString())
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('scope')
                    ->label('2. Alcance')
                    ->helperText(str('Escribe acá el **alcance** del documento, brindando una contextualización que permita entender la contibución del procedimiento aquí descrito al logro de los objetivos estratégicos. Ejm.: *Aplica a todos los documentos generados en la organización*')->inlineMarkdown()->toHtmlString())
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('references')
                    ->label('3. Referencias Normativas')
                    ->helperText(str('Escribe acá la lista de **Referencias Normativas** que aplican al documento. Una norma por campo. Ejm.: Res. 1403 de 2007')->inlineMarkdown()->toHtmlString())
                    ->schema([
                        Forms\Components\TextInput::make('title')->required()
                    ])
                    ->addActionLabel('Add Reference')
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('terms')
                    ->label('4. Términos y Definiciones')
                    ->helperText(str('Escribe acá todas las definiciones que sean requeridas para el correcto entendimiento del documento. Una definición por campo')->inlineMarkdown()->toHtmlString())
                    ->schema([
                        Forms\Components\TextInput::make('definition')->required()
                    ])
                    ->addActionLabel('Add Definition')
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('responsibilities')
                    ->label('5. Responsabilidades')
                    ->helperText(str('Escribe acá **el cargo** con su respectiva **responsabilidad**. Ejm.: *Director Técnico: Velar por el cumplimiento del procedimiento*. Una responsabilidad por campo')->inlineMarkdown()->toHtmlString())
                    ->schema([
                        Forms\Components\TextInput::make('responsibility')->required()
                    ])
                    ->addActionLabel('Add Responsibility')
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('records')
                    ->label('6. Registros')
                    ->helperText(str('Escribe acá la lista de registros si aplican. Ejm.: *Temperatura y Humedad*')->inlineMarkdown()->toHtmlString())
                    ->schema([
                        Forms\Components\TextInput::make('record')->required()
                    ])
                    ->addActionLabel('Add Record')
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('procedure')
                    ->label('7. Procedimiento')
                    ->helperText(str('Desglosa el procedimiento en *a)* el nombre de la actividad (Ejm.: *7.1. Elaborar cronograma*), 
                    *b)* descripción de actividad (Ejm.: Acceder a calendario y programar las actividades), 
                    *c)* responsable de la actividad (Ejm.: Director Técnico) y, **si aplica**, 
                    *d)* el registro que se debe realizar (Ejm.: Cronograma)')->inlineMarkdown()->toHtmlString())
                    ->schema([
                        Forms\Components\TextInput::make('activity')->required(),
                        Forms\Components\TextInput::make('description')->required(),
                        Forms\Components\TextInput::make('responsible')->required(),
                        Forms\Components\TextInput::make('records')->required(),
                    ])
                    ->addActionLabel('Add Definition')
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('annexes')
                    ->label('8. Anexos')
                    ->helperText(str('Escribe acá la lista de anexos si aplican. Ejm.: *Lista de insumos*')->inlineMarkdown()->toHtmlString())
                    ->schema([
                        Forms\Components\TextInput::make('annexe')->required()
                    ])
                    ->addActionLabel('Add Annex')
                    ->columnSpanFull(),
                Forms\Components\KeyValue::make('data')
                    ->helperText(str('Escribe acá en minúsculas
                    1. **version:** y su correspondiente valor (*ejm.: version: 01*), y 
                    2. **vigencia** y su correspondiente valor (*ejm.: vigencia: 09/07/2025)')->inlineMarkdown()->toHtmlString())
                    ->keyPlaceholder('version')
                    ->valuePlaceholder('01')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('prepared_by')
                    ->label('Prepared By')
                    ->disabled()
                    ->helperText(str('**Usuario** que elaboró el documento.')->inlineMarkdown()->toHtmlString())
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('reviewed_by')
                    ->label('Reviewed By')
                    ->disabled()
                    ->helperText(str('**Usuario** que revisó el documento.')->inlineMarkdown()->toHtmlString())
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('approved_by')
                    ->label('Approved By')
                    ->disabled()
                    ->helperText(str('**Usuario** que aprobó el documento.')->inlineMarkdown()->toHtmlString())
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label(__('Title'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('process.name')->label(__('Process'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('document_category.name')->label(__('Document Category'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated At'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('Deleted At'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('Pdf')
                        ->icon('phosphor-eye')
                        ->url(fn(Document $record) => route('document.details', ['tenant' => Filament::getTenant()->id, 'document' =>$record]))
                        ->openUrlInNewTab(),
                ])
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
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
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
