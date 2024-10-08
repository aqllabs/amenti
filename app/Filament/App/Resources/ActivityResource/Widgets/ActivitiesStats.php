<?php

namespace App\Filament\App\Resources\ActivityResource\Widgets;

use App\Models\Activity;
use DB;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActivitiesStats extends BaseWidget
{
    public Activity|null $record = null;


    protected function getStats(): array
    {

        $attendacesByStatus = $this->record->attendances()->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $stats = [];

        foreach ($attendacesByStatus as $status => $count) {
            $stats[] = Stat::make($status === '' ? "invited" : $status, $count);
        }

        return [
            ...$stats,
        ];
    }

    //count of attendaces grouped by status
}
