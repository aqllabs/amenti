<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('video_url')
                    ->required(),
                Forms\Components\RichEditor::make('content')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_published')
                    ->required(),
                Forms\Components\Toggle::make('has_quiz')->live()->helperText('Saving without toggle on will delete the quiz'),
                Forms\Components\Section::make('Add Quiz')->schema(
                    [
                        Forms\Components\Group::make()
                            ->relationship('quiz', condition: fn (Get $get) => $get('has_quiz'))
                            ->schema([
                                Forms\Components\TextInput::make('description'),
                                Forms\Components\Builder::make('structure')->label('Quiz content')->blocks([
                                    Forms\Components\Builder\Block::make('multiple_choice')
                                        ->schema([
                                            self::getHiddenUuid(),
                                            TextInput::make('question')
                                                ->label('Question')
                                                ->required(),
                                            Repeater::make('options')->schema([
                                                TextInput::make('option')
                                                    ->label('Option')
                                                    ->required(),
                                                Toggle::make('is_correct')->distinct(),
                                            ])->minItems(1),
                                        ]),
                                    Forms\Components\Builder\Block::make('short_answer')
                                        ->schema([
                                            self::getHiddenUuid(),
                                            TextInput::make('question')
                                                ->label('Question')
                                                ->required(),
                                            TextInput::make('answer')
                                                ->label('Answer')
                                                ->required(),
                                        ]),
                                    Forms\Components\Builder\Block::make('long_answer')
                                        ->schema([
                                            self::getHiddenUuid(),
                                            TextInput::make('question')
                                                ->label('Question')
                                                ->required(),
                                            Forms\Components\Textarea::make('answer')
                                                ->label('Answer')
                                                ->required(),
                                        ]),
                                ]),
                            ])->columnSpanFull(),
                    ]),
            ]);
    }

    protected static function getHiddenUuid()
    {
        return Hidden::make('id')
            ->default(fn () => Str::uuid()->toString());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('order'),

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
            ])->reorderable('order')->defaultSort('order', 'asc');
    }
}
