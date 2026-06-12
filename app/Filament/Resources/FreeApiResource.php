<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FreeApiResource\Pages;
use App\Models\FreeApi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class FreeApiResource extends Resource
{
    protected static ?string $model = FreeApi::class;
    protected static ?string $navigationIcon = 'heroicon-o-cloud-arrow-down';
    protected static ?string $navigationGroup = 'Resources';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('API Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(FreeApi::class, 'slug', ignoreRecord: true),
                        Forms\Components\TextInput::make('category')
                            ->required()
                            ->placeholder('e.g. Weather, Finance, Dev Tools'),
                        Forms\Components\RichEditor::make('documentation_html')
                            ->label('Documentation HTML Content')
                            ->required(),
                        Forms\Components\Select::make('auth_type')
                            ->options([
                                'No Auth' => 'No Auth',
                                'API Key' => 'API Key',
                                'OAuth2' => 'OAuth2',
                                'User Token' => 'User Token',
                            ])
                            ->default('No Auth')
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Visible to Public')
                            ->default(true),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category')->badge(),
                Tables\Columns\TextColumn::make('auth_type'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFreeApis::route('/'),
            'create' => Pages\CreateFreeApi::route('/create'),
            'edit' => Pages\EditFreeApi::route('/{record}/edit'),
        ];
    }
}