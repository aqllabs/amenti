<?php

namespace App\Filament\App\Resources\MeetingResource\Pages;

use App\Filament\App\Resources\MeetingResource;
use Filament\Resources\Pages\ViewRecord;

class ViewMeeting extends ViewRecord
{
    protected static string $resource = MeetingResource::class;


    protected function getHeaderWidgets(): array
    {
        return [
            MeetingResource\Widgets\MeetingStats::class,
        ];
    }

}
