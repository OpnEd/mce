<?php

namespace App\Filament\Resources\Quality\DocumentResource\Pages;

use App\Filament\Resources\Quality\DocumentResource;
use App\Models\Document;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocument extends EditRecord
{
    protected static string $resource = DocumentResource::class;

    /**
     * Campos que implican cambio de contenido y exigen reaprobación.
     *
     * @var array<int, string>
     */
    protected array $contentFields = [
        'title',
        'process_id',
        'document_category_id',
        'slug',
        'objective',
        'scope',
        'references',
        'terms',
        'responsibilities',
        'records',
        'procedure',
        'annexes',
        'data',
    ];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        /** @var Document $record */
        $record = $this->record;

        if (! $record->isApproved()) {
            return $data;
        }

        foreach ($this->contentFields as $field) {
            $newValue = $data[$field] ?? null;
            $oldValue = $record->getAttribute($field);

            if ($newValue !== $oldValue) {
                $data['data'] = is_array($data['data'] ?? null) ? $data['data'] : [];
                unset($data['data']['submitted_for_review_at'], $data['data']['submitted_for_review_by']);
                $data['prepared_by'] = auth()->id();
                $data['reviewed_by'] = null;
                $data['approved_by'] = null;
                break;
            }
        }

        return $data;
    }
}
