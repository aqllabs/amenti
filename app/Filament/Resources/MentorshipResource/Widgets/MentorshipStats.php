<?php

namespace App\Filament\Resources\MentorshipResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MentorshipStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total meetings', '12'),
            Stat::make('Activities attended together', '5'),
        ];
    }
}
