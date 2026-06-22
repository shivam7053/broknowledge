<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Filament\Imports\QuestionImporter;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationGroup = 'Quizzes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('quiz_id')
                    ->relationship('quiz', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\RichEditor::make('question_text')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('options')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label('Option Key (e.g., A, B, C, D)')
                            ->required()
                            ->maxLength(1)
                            ->rules(['alpha', 'uppercase'])
                            ->unique(ignoreRecord: true, table: 'questions', column: 'options->key')
                            ->helperText('Single uppercase letter (A, B, C, D, etc.)'),
                        Forms\Components\TextInput::make('value')
                            ->label('Option Value')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(2)
                    ->minItems(2)
                    ->maxItems(5)
                    ->grid(2)
                    ->defaultItems(4)
                    ->columnSpanFull()
                    ->helperText('Add at least 2 options. Ensure keys are unique.'),
                Forms\Components\TextInput::make('correct_option')
                    ->label('Correct Option Key')
                    ->required()
                    ->maxLength(1)
                    ->rules(['alpha', 'uppercase'])
                    ->helperText('Enter the key of the correct option (e.g., A, B, C, D)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                ImportAction::make()
                    ->importer(QuestionImporter::class)
            ])
            ->columns([
                Tables\Columns\TextColumn::make('quiz.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('question_text')
                    ->html()
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('correct_option')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('quiz_id')
                    ->relationship('quiz', 'title')
                    ->label('Quiz'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}