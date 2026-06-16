<?php

namespace App\Filament\Resources\AssetResource;

use App\Models\Asset;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class AssetImporter extends Importer
{
    protected static ?string $model = Asset::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('category')
                ->label('Category')
                ->relationship(resolveUsing: 'name')
                ->helperText('You can use the Category Name (e.g., Emoji) or the numeric ID.')
                ->requiredMapping()
                ->rules(['required', 'exists:asset_categories,name']), // Ensures category name exists
            ImportColumn::make('title')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']), // Basic validation for title
            ImportColumn::make('svg_content')
                ->requiredMapping()
                ->rules(['required', 'string']), // Basic validation for SVG content
            ImportColumn::make('keywords')
                ->castStateUsing(function ($state) {
                    if (is_array($state)) return $state;
                    return array_map('trim', explode(',', $state));
                }),
        ];
    }

    public function resolveRecord(): ?Asset
    {
        return new Asset();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your asset import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' were imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}