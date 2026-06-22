<?php

namespace App\Filament\Imports;

use App\Models\Course;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Str;

class CourseImporter extends Importer
{
    protected static ?string $model = Course::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('Laravel for Beginners'),
            ImportColumn::make('slug')
                ->rules(['nullable', 'max:255'])
                ->example('laravel-for-beginners'),
            ImportColumn::make('description')
                ->example('Master the basics of the Laravel framework with this hands-on course.'),
        ];
    }

    public function resolveRecord(): ?Course
    {
        // Prevents duplicates by finding existing courses by slug or title
        $slug = $this->data['slug'] ?? Str::slug($this->data['title'] ?? '');

        return Course::firstOrNew([
            'slug' => $slug,
        ]);
    }

    protected function afterFill(): void
    {
        // Auto-generate slug if it wasn't provided in the CSV
        if (empty($this->record->slug)) {
            $this->record->slug = Str::slug($this->record->title);
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your course import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' were imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}