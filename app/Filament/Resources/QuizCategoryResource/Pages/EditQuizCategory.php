<?php

namespace App\Filament\Resources\QuizCategoryResource\Pages;

use App\Filament\Resources\QuizCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuizCategory extends EditRecord
{
    protected static string $resource = QuizCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}