<?php

namespace App\Filament\Resources\AnesthesiaSheetResource\Pages;

use App\Filament\Resources\AnesthesiaSheetResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAnesthesiaSheet extends EditRecord
{
    protected static string $resource = AnesthesiaSheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\Action::make('closeSheet')
                ->label(__('Close Sheet'))
                ->visible(fn($record) => $record->status === 'opened')
                ->action(function ($record) {
                    $record->status = 'closed';
                    $record->save();
                    Notification::make()
                        ->title(__('Anesthesia Sheet Closed'))
                        ->success()
                        ->send();
                })->requiresConfirmation()
                ->icon('heroicon-o-check-circle')
                ->color('success')
        ];
    }
}
