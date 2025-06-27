<?php

namespace App\Filament\TenantManager\Resources\ProductResource\Pages;

use App\Filament\Exports\ProductExporter;
use Filament\Actions\Exports\Enums\ExportFormat;
use App\Filament\TenantManager\Resources\ProductResource;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Models\Export;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ExportAction::make()
                ->exporter(ProductExporter::class)
                ->formats([
                    ExportFormat::Xlsx,
                ]),
        ];
    }
}
