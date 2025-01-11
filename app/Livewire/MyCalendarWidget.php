<?php

namespace App\Livewire;


use \Guava\Calendar\Widgets\CalendarWidget;
use Illuminate\Support\Collection;

class MyCalendarWidget extends CalendarWidget
{
    public function getEvents(array $fetchInfo = []): Collection | array
    {
        return [
            // Chainable object-oriented variant
            auth()->user()->availabilities->all()
        ];
    }
}

