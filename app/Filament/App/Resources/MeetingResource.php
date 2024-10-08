<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\MeetingResource\Pages;
use App\Filament\App\Resources\MeetingResource\RelationManagers;
use App\Models\Meeting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MeetingResource extends Resource
{
    protected static ?string $model = Meeting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('start_date')
                    ->required(),
                Forms\Components\Select::make('format')
                    ->options(
                        ['ONLINE' => 'ONLINE', 'OFFLINE' => 'OFFLINE']
                    )->required(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('format')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
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
            RelationManagers\MeetingAttendeesRelationManager::class,
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('start_date'),
            TextEntry::make('address'),
            TextEntry::make('format'),
            TextEntry::make('description'),
            Section::make('Users feedback')->columns(2)->schema(
                [
                    RepeatableEntry::make('attendances')->schema([
                        TextEntry::make('user.name'),
                        TextEntry::make('status'),
                        TextEntry::make('feedback'),
                        TextEntry::make('rating'),
                        TextEntry::make('admin_feedback'),
                    ])->grid(2)
                ]
            ),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMeetings::route('/'),
            'create' => Pages\CreateMeeting::route('/create'),
            'edit' => Pages\EditMeeting::route('/{record}/edit'),
            'view' => Pages\ViewMeeting::route('/{record}'),
        ];
    }
}
