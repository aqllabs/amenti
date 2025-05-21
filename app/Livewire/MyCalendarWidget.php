<?php

namespace App\Livewire;

use Guava\Calendar\Widgets\CalendarWidget;
use Illuminate\Support\Collection;

class MyCalendarWidget extends CalendarWidget
{
    protected bool $eventClickEnabled = true;

    protected string $calendarView = 'timeGridWeek';

    protected ?string $defaultEventClickAction = 'edit';

    public function onEventClick(array $info = [], ?string $action = null): void
    {
        // do something on click
        // $info contains the event data:
        // // $info['view'] - the view object
        // /
        dd($info);
    }

    public function getEvents(array $fetchInfo = []): Collection|array
    {
        $events = auth()->user()->availabilities->all();
        $events[] = ['title' => 'My second event', 'start' => today()->addDays(3), 'end' => today()->addDays(3)];
        // create 5 more to the same day

        return $events;
    }

    public function getOptions(): array
    {
        return [
            'slotMinTime' => '08:00',
            'slotMaxTime' => '20:00',
        ];
    }
}
