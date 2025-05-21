<?php

namespace App\Filament\Resources\MentorshipResource\Pages;

use App\Filament\Resources\MentorshipResource;
use Filament\Resources\Pages\ViewRecord;

class ViewMentorship extends ViewRecord
{
    protected static string $resource = MentorshipResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            MentorshipResource\Widgets\MentorshipStats::class,
        ];
    }
}
