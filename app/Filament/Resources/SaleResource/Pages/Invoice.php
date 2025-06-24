<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use App\Models\Sale;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Resources\Pages\Page;

class Invoice extends Page
{
    protected static string $resource = SaleResource::class;

    protected static string $view = 'filament.resources.sale-resource.pages.invoice';
    public $record;
    public $sale;
    public $settings;

    public function mount($record)
    {
        $this->record = $record;
        $this->sale = Sale::with([
            'team',
            'customer',
            'user',
            'factura',
            'items'
        ])->findOrFail($record);
        $this->settings = getSettings([
            'Team Name',
            'Address',
            'E-mail',
        ], Filament::getTenant()->id);
    }

    public function getHeaderActions(): array
    {
        return [
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
