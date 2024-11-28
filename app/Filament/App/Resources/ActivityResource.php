<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ActivityResource\Pages;
use App\Filament\App\Resources\ActivityResource\RelationManagers\AttendeesRelationManager;
use App\Models\Activity;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('activity_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image_urls')
                    ->image(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('start_date')
                    ->required(),
                Forms\Components\DateTimePicker::make('end_date'),
                Forms\Components\TextInput::make('duration')
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'DRAFT' => 'DRAFT', 'ACTIVE' => 'ACTIVE', 'CANCELLED' => 'CANCELLED', 'DELETED' => 'DELETED'
                    ])
                    ->default('DRAFT'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('activity_name'),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime('D, d M  H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('address'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
            'view' => Pages\ViewActivity::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            AttendeesRelationManager::class,
        ];
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('activity_name'),
                TextEntry::make('description'),
                TextEntry::make('start_date'),
                TextEntry::make('address'),
            ]);
    }


}
