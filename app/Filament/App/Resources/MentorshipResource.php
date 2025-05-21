<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\MentorshipResource\Pages;
use App\Models\Mentorship;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MentorshipResource extends Resource
{
    protected static ?string $model = Mentorship::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $tenantOwnershipRelationshipName = 'team';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('mentor_id')
                    ->label('Mentor')
                    ->options(
                        User::where('user_type', 'mentor')
                            ->whereHas('teams', function ($query) {
                                $query->where('team_id', Filament::getTenant()->id);
                            })
                            ->pluck('name', 'id')
                    )
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('mentee_id')
                    ->label('Mentee')
                    ->options(User::where('user_type', 'mentee')->whereHas('teams', function ($query) {
                        $query->where('team_id', Filament::getTenant()->id);
                    })->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload(),
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
