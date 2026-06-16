<?php

namespace App\Filament\Resources;

use App\Models\Asset;
use Filament\Forms;
use App\Filament\Resources\AssetResource\Pages;
use App\Filament\Resources\AssetResource\AssetImporter;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ImportAction;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;
    protected static ?string $navigationIcon = 'heroicon-o-face-smile';
    protected static ?string $navigationGroup = 'Assets';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('asset_category_id')
                ->relationship('category', 'name')
                ->required(),
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\Textarea::make('svg_content')
                ->label('SVG Raw Code')
                ->required()
                ->columnSpanFull(),
            Forms\Components\TagsInput::make('keywords')
                ->placeholder('Add keywords for search...')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                ImportAction::make()
                    ->importer(AssetImporter::class)
            ])
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('category.name'),
                Tables\Columns\TextColumn::make('keywords'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
        ];
    }
}