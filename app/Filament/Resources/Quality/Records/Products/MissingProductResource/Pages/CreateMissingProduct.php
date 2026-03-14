<?php

namespace App\Filament\Resources\Quality\Records\Products\MissingProductResource\Pages;

use App\Filament\Resources\Quality\Records\Products\MissingProductResource;
use App\Models\Quality\Records\Products\MissingProduct;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateMissingProduct extends CreateRecord
{
    protected static string $resource = MissingProductResource::class;

    public function getHeading(): string
    {
        return __('Registrar Faltantes');
    }

    public function getSubheading(): ?string
    {
        return __('Se consideran faltantes los productos que se nos están agotando o ya están en cero (0), y los registramos para comprarlos en el próximo pedido. Pero para sacar los indicadores de gestión de Selección y Adquisición debemos registrar algunos detalles: A) Si el producto es de alta rotación (seleccionado), fue solicitado por el usuario y no lo teníamos, y B) Si el producto es de baja rotación (no seleccionado), fue solicitado por el usuario y no lo teníamos.');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['team_id'] = Filament::getTenant()->id;
        $data['user_id'] = auth()->id();
        $requestedByUser = ($data['requested_by_user'] ?? false) === true;
        $isSelected = $data['is_selected'] ?? null;

        if ($isSelected === false) {
            $data['requested_by_user'] = true;
            $requestedByUser = true;
        }

        if ($requestedByUser) {
            $data['stock_status'] = MissingProduct::STOCK_STATUS_OUT_OF_STOCK;
            if ($isSelected === null) {
                $data['is_selected'] = true;
            }
        }
        return $data;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Datos registrados')
            ->body('La información de faltantes fue registrada con éxito!');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
