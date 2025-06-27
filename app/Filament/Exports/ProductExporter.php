<?php

namespace App\Filament\Exports;

use App\Models\Product;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Schema;

class ProductExporter extends Exporter
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('product_category.name')
                ->label(__('Category')),

            ExportColumn::make('pharmaceutical_form.name')
                ->label(__('Pharmaceutical Form')),

            ExportColumn::make('bar_code')
                ->label(__('Code')),

            ExportColumn::make('name')
                ->label(__('Commercial Name')),

            ExportColumn::make('drug')
                ->label(__('Active Ingredient')),

            ExportColumn::make('description')
                ->label(__('Commercial Description')),

            ExportColumn::make('tax')
                ->label(__('Tax')),

            ExportColumn::make('status')
                ->label(__('Status')),
        ];
    }

    public static function modifyQuery(Builder $query): Builder
    {
        // Verifica si la columna 'team_id' existe antes de aplicar el filtro
        if (Schema::hasColumn((new Product)->getTable(), 'team_id')) {
            $team = Filament::getTenant();

            // Si hay un equipo, filtra por team_id; si no, exporta productos sin team asignado (team_id null)
            if ($team) {
                return $query->where('team_id', $team->id)
                             ->with(['team']);
            }

            return $query->whereNull('team_id')
                         ->with(['team']);
        }

        // Si no existe la columna, solo retorna el query original
        return $query;
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your product export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
