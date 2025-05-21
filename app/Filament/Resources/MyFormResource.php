<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MyFormResource\Pages;
use App\Filament\Resources\MyFormResource\RelationManagers\FormSubmissionRelationManager;
use App\Models\MyForm;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MyFormResource extends Resource
{
    protected static ?string $model = MyForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Mentorship';

    protected static function getHiddenUuid()
    {
        return Hidden::make('id')
            ->default(fn () => Str::uuid()->toString());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
                Builder::make('structure')->blocks([
                    Builder\Block::make('short_answer')
                        ->schema([
                            self::getHiddenUuid(),
                            TextInput::make('question')
                                ->label('Question')
                                ->required(),
                            Toggle::make('required')->label('Required')->default(false),
                        ])
                        ->columns(2),
                    Builder\Block::make('long_answer')
                        ->schema([
                            self::getHiddenUuid(),

                            TextInput::make('question')
                                ->label('Question')
                                ->required(),
                            Toggle::make('required')->label('Required')->default(false),
                        ])
                        ->columns(2),
                    Builder\Block::make('select')
                        ->schema([
                            self::getHiddenUuid(),
                            TextInput::make('question')
                                ->label('Question')
                                ->required(),
                            Repeater::make('options')->simple(
                                TextInput::make('value')
                                    ->required(),
                            )->minItems(1),
                            Toggle::make('required')->label('Required')->default(false),
                        ]),
                    Builder\Block::make('multiple_choice')
                        ->schema([
                            self::getHiddenUuid(),
                            TextInput::make('question')
                                ->label('Question')
                                ->required(),
                            Repeater::make('options')->simple(
                                TextInput::make('value')
                                    ->required(),
                            )->minItems(1),
                            Toggle::make('required')->label('Required')->default(false),
                        ]),
                    Builder\Block::make('checkboxes')
                        ->schema([
                            self::getHiddenUuid(),
                            TextInput::make('question')
                                ->label('Question')
                                ->required(),
                            Repeater::make('options')->simple(
                                TextInput::make('value')
                                    ->required(),
                            )->minItems(2),
                            Toggle::make('required')->label('Required')->default(false),
                        ]),
                    Builder\Block::make('file_upload')
                        ->schema([
                            self::getHiddenUuid(),
                            TextInput::make('question')
                                ->label('Question')
                                ->required(),
                            Toggle::make('required')->label('Required')->default(false),
                        ])
                        ->columns(2),
                ])->columnSpan(2),

                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('is_active'),
                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            FormSubmissionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyForms::route('/'),
            'create' => Pages\CreateMyForm::route('/create'),
            'edit' => Pages\EditMyForm::route('/{record}/edit'),
        ];
    }
}
