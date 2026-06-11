<?php

namespace App\Filament\Resources\QuizResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question_text')
            ->columns([
                Tables\Columns\TextColumn::make('question_text')
                    ->html()
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('correct_option'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}