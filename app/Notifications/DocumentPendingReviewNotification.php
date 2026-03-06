<?php

namespace App\Notifications;

use App\Models\Document;
use App\Models\Team;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class DocumentPendingReviewNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Document $document,
        public Team $team,
        public ?User $submittedBy = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        try {
            $url = route('filament.admin.resources.quality.documents.edit', [
                'tenant' => $this->team,
                'record' => $this->document,
            ]);
        } catch (\Throwable $e) {
            Log::error('DocumentPendingReviewNotification: error creating URL', [
                'error' => $e->getMessage(),
                'team_id' => $this->team->id ?? null,
                'document_id' => $this->document->id ?? null,
            ]);
            $url = '#';
        }

        $sender = $this->submittedBy?->name ?? 'Un usuario';

        $notification = FilamentNotification::make()
            ->title('Documento pendiente de revisión')
            ->body("{$sender} envió \"{$this->document->title}\" para revisión/aprobación.")
            ->warning()
            ->actions([
                Action::make('open')
                    ->label('Abrir documento')
                    ->url($url)
                    ->button(),
            ]);

        return array_merge($notification->getDatabaseMessage(), [
            'team_id' => $this->team->id,
            'document_id' => $this->document->id,
            'document_slug' => $this->document->slug,
            'submitted_by' => $this->submittedBy?->id,
            'submitted_by_name' => $sender,
            'submitted_for_review_at' => now()->toDateTimeString(),
        ]);
    }
}

