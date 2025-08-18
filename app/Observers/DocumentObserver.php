<?php

// app/Observers/DocumentObserver.php
namespace App\Observers;

use App\Models\Document;
use App\Models\Quality\DocumentVersion;
use Illuminate\Support\Facades\Auth;

class DocumentObserver
{
    public function updating(Document $doc)
    {
        $dirty = $doc->getDirty();           // campos modificados
        $original = $doc->getOriginal();     // valores antiguos

        $changes = [];
        foreach ($dirty as $field => $new) {
            // opcional: omitir campos irrelevantes como timestamps
            if (in_array($field, ['updated_at'])) {
                continue;
            }
            $changes[$field] = [
                'old' => $original[$field] ?? null,
                'new' => $new,
            ];
        }

        if (!empty($changes)) {
            DocumentVersion::create([
                'team_id'     => $doc->team_id,
                'document_id' => $doc->id,
                'user_id'     => Auth::id(),
                'changes'     => $changes,
                'comment'     => $comment ?? null,
            ]);
        }
    }
}
