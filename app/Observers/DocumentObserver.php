<?php

// app/Observers/DocumentObserver.php
namespace App\Observers;

use App\Models\Document;
use App\Models\Quality\DocumentVersion;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DocumentObserver
{
    public function updating(Document $doc)
    {
        $dirty    = $doc->getDirty();
        $original = $doc->getOriginal(); // valores antes del cambio [web:8]

        $changes = [];

        $userFields = ['prepared_by', 'reviewed_by', 'approved_by'];

        foreach ($dirty as $field => $new) {
            // omitir campos irrelevantes
            if (in_array($field, ['updated_at'])) {
                continue;
            }

            // Si es uno de los campos de usuario, mapeamos ID -> nombre
            if (in_array($field, $userFields)) {
                $oldId = $original[$field] ?? null;
                $newId = $new;

                $oldUserName = $oldId ? optional(User::find($oldId))->name : null;
                $newUserName = $newId ? optional(User::find($newId))->name : null;

                $changes[$field] = [
                    'old' => $oldUserName,
                    'new' => $newUserName,
                ];

                continue;
            }

            // Resto de campos se guardan tal cual
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
                'comment'     => null,
            ]);
        }
    }
}
