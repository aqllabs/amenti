<?php

namespace App\Filament\App\Resources\MentorshipResource\Pages;

use App\Filament\App\Resources\MentorshipResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMentorship extends EditRecord
{
    protected static string $resource = MentorshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
