<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MentorshipResource\Pages;
use App\Filament\Resources\MentorshipResource\RelationManagers;
use App\Models\Mentorship;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MentorshipResource extends Resource
{
    protected static ?string $model = Mentorship::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Mentorship';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //get users from the team that have role mentor
                Forms\Components\Select::make('mentor_id')->options(User::role('mentor')->get()),
                Forms\Components\Select::make('mentee_id')->options(User::role('mentee')->get()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mentor.name'),
                Tables\Columns\TextColumn::make('mentee.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListMentorships::route('/'),
            'create' => Pages\CreateMentorship::route('/create'),
            'edit' => Pages\EditMentorship::route('/{record}/edit'),
            'view' => Pages\ViewMentorship::route('/{record}'),
        ];
    }
}
