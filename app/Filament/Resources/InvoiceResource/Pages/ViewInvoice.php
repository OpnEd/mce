<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label(__('Edit'))
                ->icon('phosphor-pencil-simple')
                ->color('primary'),
            Action::make('print')
                ->label(__('Print'))
                ->icon('phosphor-printer')
                ->color('primary')
                ->url(route('invoice.print', ['id' => $this->record])),
            Action::make('download')
                ->label(__('Download'))
                ->icon('phosphor-download')
                ->color('primary')
                ->url(route('invoice.download', ['id' => $this->record]))
                ->openUrlInNewTab(),
            Action::make('email')
                ->label(__('Email'))
                ->icon('phosphor-paper-plane-tilt')
                ->color('primary')
                ->url(route('invoice.email', ['id' => $this->record])),
        ];
    }
}