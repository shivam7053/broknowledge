<?php

namespace App\Filament\Resources\TopicResource\Pages;

use App\Filament\Resources\TopicResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTopics extends ListRecords
{
    protected static string $resource = TopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\ImportAction::make()
                ->importer(\App\Filament\Imports\TopicImporter::class),
            Actions\CreateAction::make(),
        ];
    }
}