<?php

namespace App\Filament\Resources\MeetingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MeetingAttendeesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendances';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('status')->options(
                    [
                        'ATTENDED' => 'ATTENDED',
                        'ACCEPTED' => 'ACCEPTED',
                        'NOSHOW' => 'NOSHOW',
                        'REJECTED' => 'REJECTED',
                        'INVITED' => 'INVITED',
                    ]
                )
                    ->required()
                    ->hiddenOn('create'),
                Forms\Components\Textarea::make('admin_feedback')
                    ->required()
                    ->hiddenOn('create'),


            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Meeting Attendance')
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('user.email'),
                Tables\Columns\TextColumn::make('status'),
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
                Tables\Actions\ViewAction::make()

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
