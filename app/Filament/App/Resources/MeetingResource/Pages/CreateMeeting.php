<?php

namespace App\Filament\App\Resources\MeetingResource\Pages;

use App\Filament\App\Resources\MeetingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMeeting extends CreateRecord
{

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }

    protected static string $resource = MeetingResource::class;
}
