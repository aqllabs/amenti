<?php

namespace App\Filament\Resources\MyFormResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class FormSubmissionRelationManager extends RelationManager
{
    protected static string $relationship = 'formSubmissions';

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                self::createFormSchema($this->getOwnerRecord()->structure)
            );
    }

    private static function createFormSchema(
        $structure,
    ) {
        return collect($structure)->map(function ($block) {
            return match ($block['type']) {
                'short_answer' => Forms\Components\TextInput::make('responses.'.$block['data']['id']),
                'long_answer' => Forms\Components\Textarea::make('responses.'.$block['data']['id']),
                'checkboxes' => Forms\Components\CheckboxList::make('responses.'.$block['data']['id'])
                    ->options(array_combine($block['data']['options'], $block['data']['options'])),
                'select' => Forms\Components\Select::make('responses.'.$block['data']['id'])
                    ->options(array_combine($block['data']['options'], $block['data']['options'])),
                default => null,
            };
        })->toArray();
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->id();

                    return $data;
                }),
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
