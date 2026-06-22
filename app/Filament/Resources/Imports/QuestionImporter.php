<?php

namespace App\Filament\Imports;

use App\Models\Question;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class QuestionImporter extends Importer
{
    protected static ?string $model = Question::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('quiz')
                ->relationship(resolveUsing: 'title')
                ->requiredMapping()
                ->example('General Knowledge Quiz'),
            ImportColumn::make('question_text')
                ->requiredMapping()
                ->example('What is the capital of France?'),
            ImportColumn::make('option_a')
                ->label('Option A')
                ->example('Paris')
                ->fillRecordUsing(fn () => null),
            ImportColumn::make('option_b')
                ->label('Option B')
                ->example('London')
                ->fillRecordUsing(fn () => null),
            ImportColumn::make('option_c')
                ->label('Option C')
                ->example('Berlin')
                ->fillRecordUsing(fn () => null),
            ImportColumn::make('option_d')
                ->label('Option D')
                ->example('Madrid')
                ->fillRecordUsing(fn () => null),
            ImportColumn::make('correct_option')
                ->requiredMapping()
                ->example('A')
                ->castStateUsing(function (string $state): string {
                    if (str_starts_with(strtolower($state), 'option_')) {
                        return strtoupper(substr($state, -1));
                    }
                    return strtoupper($state);
                }),
        ];
    }

    public function resolveRecord(): ?Question
    {
        return new Question();
    }

    protected function afterFill(): void
    {
        $options = [];
        
        foreach (['A' => 'option_a', 'B' => 'option_b', 'C' => 'option_c', 'D' => 'option_d'] as $key => $column) {
            if (!empty($this->data[$column])) {
                $options[] = [
                    'key' => $key,
                    'value' => $this->data[$column],
                ];
            }
        }

        $this->record->options = $options;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your question import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' were imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}