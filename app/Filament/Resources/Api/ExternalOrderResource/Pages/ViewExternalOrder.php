<?php

namespace App\Filament\Resources\Api\ExternalOrderResource\Pages;

use App\Filament\Resources\Api\ExternalOrderResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ViewExternalOrder extends ViewRecord
{
    protected static string $resource = ExternalOrderResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        Auth::user()->unreadNotifications()
            ->where('data->order_id', $this->record->id)
            ->get()
            ->markAsRead();

        $userTeamId = Filament::getTenant()->id;

        // Verificar que la orden no está asignada
        if ($this->record->team_id && $this->record->team_id !== $userTeamId) {
            
            Notification::make()
                ->title('Orden ya asignada')
                ->body('Esta orden ya fue asignada a otro equipo.')
                ->danger()
                ->send();

            $this->redirect(ExternalOrderResource::getUrl());

            return;
        }
    }

    protected function getHeaderActions(): array
    {
        $isTaken = $this->record->team_id !== null;

        return [
            Action::make('take_order')
                ->label($isTaken ? 'Orden Tomada' : 'Tomar Orden')
                ->modalIcon($isTaken ? 'heroicon-m-check-circle' : 'heroicon-m-check-circle')
                ->color($isTaken ? 'success' : 'warning')
                ->form([
                    Section::make('Items Disponibles')
                        ->description('Marca los items que tienes disponibles')
                        ->schema([
                            Repeater::make('items')
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Producto')
                                        ->disabled()
                                        ->dehydrated()
                                        ->columnSpan(4),

                                    TextInput::make('qty')
                                        ->label('Cantidad')
                                        ->type('number')
                                        ->disabled()
                                        ->columnSpan(1),

                                    Checkbox::make('available')
                                        ->label('¿Disponible?')
                                        ->inline(false)
                                        ->columnSpan(1)
                                        ->disabled($isTaken),
                                ])
                                ->columns(6)
                                ->addable(false)
                                ->deletable(false)
                                ->reorderable(false)
                                ->default(fn() => $this->record->items->map(fn($item) => [
                                    'name' => $item->name,
                                    'qty' => $item->qty,
                                    'available' => false,
                                ])->toArray())
                                ->columnSpanFull(),
                        ]),
                ])
                ->action(fn(array $data) => $this->takeOrder($data))
                ->requiresConfirmation()
                ->successRedirectUrl(ExternalOrderResource::getUrl())
                ->modalHeading('Confirmar asignación')
                ->modalDescription('¿Estás seguro de que quieres tomar esta orden?')
                ->disabled($isTaken)
                ->tooltip($isTaken ? 'Esta orden ya fue asignada al equipo: ' . $this->record->team?->name : null)
                ->modalWidth(MaxWidth::ThreeExtraLarge),
        ];
    }

    protected function takeOrder(array $data): void
    {
        try {
            // Validar que TODOS los items están checked
            $items = $data['items'] ?? [];
            $allChecked = collect($items)->every(fn($item) => $item['available'] ?? false);

            if (! $allChecked) {
                Notification::make()
                    ->title('❌ Todos los items deben estar disponibles')
                    ->body('Toma la orden solo si tienes TODOS los items disponibles.')
                    ->warning()
                    ->send();

                return;
            }

            $teamId = Filament::getTenant()->id;
            // Asignación atómica
            $updated = DB::transaction(function () use ($teamId) {
                // Refresca y bloquea la fila
                $order = $this->record->newQuery()
                    ->where('id', $this->record->id)
                    ->whereNull('team_id')                // Solo si aún no está tomada
                    ->lockForUpdate()                     // Pessimistic locking
                    ->first();

                if (! $order) {
                    return false;
                }

                $order->update([
                    'team_id' => $teamId,
                    'claimed_at' => now(),
                    'claimed_by' => Auth::user()->id,
                ]);

                return true;
            });

            if (! $updated) {

                Notification::make()
                    ->title('Orden ya fue tomada')
                    ->body('Otro equipo tomó esta orden un instante antes que tú.')
                    ->warning()
                    ->send();

                $this->redirect(ExternalOrderResource::getUrl());

                return;
            }

            Notification::make()
                ->title('Orden asignada')
                ->body("Orden {$this->record->external_order_id} tomada exitosamente")
                ->success()
                ->send();

            // Refrescar el record para que el botón se deshabilite
            $this->record->refresh();
            $this->mount($this->record->id);
        } catch (\Exception $e) {

            Notification::make()
                ->title('Error')
                ->body('No se pudo asignar la orden: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
