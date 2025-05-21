<?php

namespace App\Filament\App\Resources\MentorshipResource\Pages;

use App\Filament\App\Resources\MentorshipResource;
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
