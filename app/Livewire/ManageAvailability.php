<?php

namespace App\Livewire;

use App\Models\Availability;
use Livewire\Component;
use Illuminate\Support\Carbon;

class ManageAvailability extends Component
{
    public $date;
    public $startTime;
    public $endTime;
    public $notes;
    public $availabilities = [];

    protected $rules = [
        'date' => 'required|date',
        'startTime' => 'required',
        'endTime' => 'required|after:startTime',
        'notes' => 'nullable|string',
    ];

    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
        $this->loadAvailabilities();
    }

    public function loadAvailabilities()
    {
        $this->availabilities = auth()->user()->availabilities()
            ->whereDate('date', $this->date)
            ->orderBy('start_time')
            ->get();
    }

    public function saveAvailability()
    {
        $this->validate();

        auth()->user()->availabilities()->create([
            'date' => $this->date,
            'start_time' => $this->startTime,
            'end_time' => $this->endTime,
            'notes' => $this->notes,
        ]);

        $this->reset(['startTime', 'endTime', 'notes']);
        $this->loadAvailabilities();
    }

    public function deleteAvailability($id)
    {
        Availability::destroy($id);
        $this->loadAvailabilities();
    }

    public function render()
    {
        return view('livewire.manage-availability');
    }
}
