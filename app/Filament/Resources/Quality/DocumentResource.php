<?php

namespace App\Filament\Resources\Quality;

use App\Filament\Resources\Quality\DocumentResource\Pages;
use App\Filament\Resources\Quality\DocumentResource\RelationManagers;
use App\Models\Document;
use App\Models\User;
use App\Notifications\DocumentPendingReviewNotification;
use Filament\Facades\Filament;
use Filament\GlobalSearch\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\Unique;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Forms\Get;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Support\Facades\Notification;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?int $navigationSort = 15;
    protected static ?string $navigationGroup = 'Plataforma Estratégica';
    protected static ?string $navigationLabel = 'Procedimientos';
    protected static ?string $pluralModelLabel = 'Procedimientos';
    protected static ?string $modelLabel = 'Procedimiento';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $slug = 'documentacion-sistema-de-gestion';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('fields.title'))
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
                    ->label(__('fields.process'))
                    ->helperText(str('Selecciona el **Proceso** al que pertenece el documento. Ejm.: *Gestión de Documentos*')->inlineMarkdown()->toHtmlString())
                    ->relationship('process', 'name')
                    ->required(),
                Forms\Components\Select::make('document_category_id')
                    ->label(__('fields.document_category'))
                    ->helperText(str('Selecciona la **Categorí­a de Documento** a la que pertenece el documento. Ejm.: *Procedimiento*')->inlineMarkdown()->toHtmlString())
                    ->relationship('document_category', 'name')
                    ->required(),
                Forms\Components\TextInput::make('slug')
                    ->label(__('Slug'))
                    ->required()
                    ->maxLength(255)
                    ->unique(
                        table: Document::class,
                        column: 'slug',
                        ignoreRecord: true,
                        modifyRuleUsing: fn (Unique $rule): Unique => $rule->where(
                            'team_id',
                            Filament::getTenant()?->id
                        )
                    ),
                Forms\Components\Textarea::make('objective')
                    ->label('1. Objetivo')
                    ->maxLength(65535)
                    ->helperText(str('Escribe acá el **objetivo** del documento. Ejm.: *Establecer los lineamientos para la gestión de documentos*')->inlineMarkdown()->toHtmlString())
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('scope')
                    ->label('2. Alcance')
                    ->helperText(str('Escribe acá el **alcance** del documento, brindando una contextualización que permita entender la contibución del procedimiento aquí­ descrito al logro de los objetivos estratégicos. Ejm.: *Aplica a todos los documentos generados en la organización*')->inlineMarkdown()->toHtmlString())
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
                Fieldset::make('')
                    ->schema([
                        Forms\Components\Placeholder::make('prepared_by_name')
                            ->label('Elaborado por')
                            ->content(fn(?Document $record): string => $record?->preparedBy?->name ?? '-')
                            ->helperText(str('**Usuario** que elaboró el documento.')->inlineMarkdown()->toHtmlString()),
                        Forms\Components\Placeholder::make('reviewed_by_name')
                            ->label('Revisado por')
                            ->content(fn(?Document $record): string => $record?->reviewedBy?->name ?? '-')
                            ->helperText(str('**Usuario** que revisó el documento.')->inlineMarkdown()->toHtmlString()),
                        Forms\Components\Placeholder::make('approved_by_name')
                            ->label('Aprobado por')
                            ->content(fn(?Document $record): string => $record?->approvedBy?->name ?? '-')
                            ->helperText(str('**Usuario** que aprobó el documento.')->inlineMarkdown()->toHtmlString()),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label(__('Title'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('process.name')->label(__('Process'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('document_category.name')->label(__('Document Category'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('workflow_status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        Document::STATUS_PREPARATION => 'Preparación',
                        Document::STATUS_REVIEW => 'Revisión',
                        Document::STATUS_APPROVED => 'Aprobado',
                        default => $state,
                    })
                    ->colors([
                        'warning' => Document::STATUS_PREPARATION,
                        'info' => Document::STATUS_REVIEW,
                        'success' => Document::STATUS_APPROVED,
                    ]),
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
                    Tables\Actions\Action::make('submitForReview')
                        ->label('Enviar a revisión')
                        ->icon('heroicon-m-paper-airplane')
                        ->visible(fn(Document $record): bool => $record->isInPreparation())
                        ->action(function (Document $record): void {
                            if (! $record->prepared_by) {
                                FilamentNotification::make()
                                    ->title('El documento debe tener elaborador.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $data = $record->data ?? [];
                            $data['submitted_for_review_at'] = now()->toDateTimeString();
                            $data['submitted_for_review_by'] = auth()->id();

                            $record->data = $data;
                            $record->reviewed_by = null;
                            $record->approved_by = null;
                            $record->save();

                            $tenant = Filament::getTenant();
                            if ($tenant) {
                                $usersToNotify = User::query()
                                    ->whereHas('teams', fn ($query) => $query->where('teams.id', $tenant->id))
                                    ->get()
                                    ->filter(function (User $user): bool {
                                        $currentUserId = auth()->id();

                                        return (int) $user->id !== (int) $currentUserId
                                            && $user->can('edit-document');
                                    });

                                if ($usersToNotify->isNotEmpty()) {
                                    Notification::send(
                                        $usersToNotify,
                                        new DocumentPendingReviewNotification($record, $tenant, auth()->user())
                                    );
                                }
                            }

                            FilamentNotification::make()
                                ->title('Documento enviado a revisión.')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('markReviewed')
                        ->label('Marcar revisión')
                        ->icon('heroicon-m-check-circle')
                        ->visible(fn(Document $record): bool => $record->isInPreparation() || $record->isInReview())
                        ->action(function (Document $record): void {
                            $userId = auth()->id();
                            $data = $record->data ?? [];

                            if (! $record->prepared_by) {
                                FilamentNotification::make()
                                    ->title('No se puede revisar: falta elaborador.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            if ((int) $record->prepared_by === (int) $userId) {
                                FilamentNotification::make()
                                    ->title('El revisor debe ser distinto del elaborador.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $data['submitted_for_review_at'] ??= now()->toDateTimeString();
                            $data['submitted_for_review_by'] ??= $userId;

                            $record->data = $data;
                            $record->reviewed_by = $userId;
                            $record->approved_by = null;
                            $record->save();

                            FilamentNotification::make()
                                ->title('Documento revisado.')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('approve')
                        ->label('Aprobar')
                        ->icon('heroicon-m-shield-check')
                        ->visible(fn(Document $record): bool => ! $record->isApproved())
                        ->action(function (Document $record): void {
                            $userId = auth()->id();

                            if (! $record->prepared_by || ! $record->reviewed_by) {
                                FilamentNotification::make()
                                    ->title('Debe estar elaborado y revisado antes de aprobar.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            if (
                                (int) $record->prepared_by === (int) $userId
                                || (int) $record->reviewed_by === (int) $userId
                            ) {
                                FilamentNotification::make()
                                    ->title('El aprobador debe ser distinto del elaborador y del revisor.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $record->approved_by = $userId;
                            $record->save();

                            FilamentNotification::make()
                                ->title('Documento aprobado.')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('returnToPreparation')
                        ->label('Regresar a preparación')
                        ->icon('heroicon-m-arrow-uturn-left')
                        ->visible(fn(Document $record): bool => ! $record->isInPreparation())
                        ->requiresConfirmation()
                        ->action(function (Document $record): void {
                            $record->prepared_by = auth()->id();
                            $record->reviewed_by = null;
                            $record->approved_by = null;
                            $data = $record->data ?? [];
                            unset($data['submitted_for_review_at'], $data['submitted_for_review_by']);
                            $record->data = $data;
                            $record->save();

                            FilamentNotification::make()
                                ->title('Documento devuelto a preparación.')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('Pdf')
                        ->icon('phosphor-eye')
                        ->url(function (Document $record) {
                            $tenant = Filament::getTenant();
                            $url = route('document.details', ['tenant' => $tenant?->id, 'document' => $record]);

                            return $url;
                        })
                        ->openUrlInNewTab(),
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

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        $tenant = Filament::getTenant();

        return route('document.details', [
            'tenant' => $tenant?->id,
            'document' => $record,
        ]);
    }

    public static function getGlobalSearchResultActions(Model $record): array
    {
        $tenant = Filament::getTenant();

        return [
            Action::make('pdf')
                ->label('PDF')
                ->url(route('document.details', [
                    'tenant' => $tenant?->id,
                    'document' => $record,
                ]), shouldOpenInNewTab: true),
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getPendingApprovalCount();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Pendientes de aprobacion: ' . static::getPendingApprovalCount();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    protected static function getPendingApprovalCount(): int
    {
        $tenantId = Filament::getTenant()?->id;

        if (! $tenantId) {
            return 0;
        }

        return Document::query()
            ->where('team_id', $tenantId)
            ->whereNull('approved_by')
            ->where(function (Builder $query): void {
                $query
                    ->whereNotNull('reviewed_by')
                    ->orWhereNotNull('data->submitted_for_review_at');
            })
            ->count();
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

