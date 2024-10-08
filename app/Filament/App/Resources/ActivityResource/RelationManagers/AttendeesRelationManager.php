<?php

namespace App\Filament\App\Resources\ActivityResource\RelationManagers;

use App\Models\ActivityAttendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AttendeesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendances';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('Name')->content(fn(ActivityAttendance $act) => $act->user->name),
                Forms\Components\Placeholder::make('Email')->content(fn(ActivityAttendance $act) => $act->user->email),
                Forms\Components\Select::make('status')
                    ->options(
                        [
                            'ATTENDED' => 'ATTENDED',
                            'ACCEPTED' => 'ACCEPTED',
                            'NOSHOW' => 'NOSHOW',
                            'REJECTED' => 'REJECTED'
                        ]
                    )
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Attendance')
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('user.email'),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(
                    [
                        'ATTENDED' => 'ATTENDED',
                        'ACCEPTED' => 'ACCEPTED',
                        'NOSHOW' => 'NOSHOW',
                        'REJECTED' => 'REJECTED'
                    ]
                )
            ], Tables\Enums\FiltersLayout::AboveContent)
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
