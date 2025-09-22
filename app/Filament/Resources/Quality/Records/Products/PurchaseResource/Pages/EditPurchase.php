<?php

namespace App\Filament\Resources\Quality\Records\Products\PurchaseResource\Pages;

use App\Filament\Resources\Quality\Records\Products\PurchaseResource;
use App\Models\Purchase;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;

class EditPurchase extends EditRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->icon('phosphor-trash'),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
            /* Action::make('confirmPurchase')
                ->label('Confirm')
                ->color('success')
                ->icon('phosphor-check-square')
                ->requiresConfirmation() // Opcional: Modal de confirmación
                ->hidden(fn(Purchase $record): bool => $record->status !== 'pending')
                ->authorize(
                    fn(Purchase $record) => Gate::allows('confirm', $record)
                ),
                ->action(function (Purchase $record) {
                    try {
                        DB::transaction(function () use ($record) {
                            $record->update([
                                'status' => 'confirmed',
                                'confirmed_at' => now()
                            ]);
                        });

                        Notification::make()
                            ->title('Order confirmed')
                            ->color('success')
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error al confirmar')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();

                        throw $e;
                    }
                }) */
        ];
    }

    public function getHeading(): string
    {
        return __('Edición de órdenes de compra');
    }

    public function getContentTabIcon(): ?string
    {
        return 'heroicon-m-cog';
    }

    /* #[On('purchaseTotalUpdated')]
    public function refreshForm(): void
    {
        parent::refreshFormData(array_keys($this->record->toArray()));
    } */

}