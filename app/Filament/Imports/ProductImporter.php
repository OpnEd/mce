<?php

namespace App\Filament\Imports;

use App\Models\Product;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('product_category.name')
                ->label(__('Category'))
                ->required()
                ->rules(['string', 'max:255']),
            ImportColumn::make('pharmaceutical_form.name')
                ->label(__('Pharmaceutical Form'))
                ->required()
                ->rules(['string', 'max:255']),
            ImportColumn::make('bar_code')
                ->label(__('Code'))
                ->required()
                ->rules(['string', 'max:255']),
            ImportColumn::make('name')
                ->label(__('Commercial Name'))
                ->required()
                ->rules(['string', 'max:255']),
            ImportColumn::make('drug')
                ->label(__('Active Ingredient'))
                ->required()
                ->rules(['string', 'max:255']),
            ImportColumn::make('description')
                ->label(__('Commercial Description'))
                ->required(),
            ImportColumn::make('tax')
                ->label(__('Tax')),
            ImportColumn::make('status')
                ->label(__('Status')),
        ];
    }

    public function resolveRecord(): ?Product
    {
        // return Product::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Product();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your product import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
