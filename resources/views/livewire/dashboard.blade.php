<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new #[Layout("layouts.app")] class extends Component {
    public function with(): array
    {
        return [
            "activities" => Auth::user()->activities,
            "meetings" => Auth::user()
                ->meetings()
                ->with("attendances.user")
                ->get(),
            "mentors" => Auth::user()->mentors,
        ];
    }
};
?>

<div class="container mx-auto p-6">
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-10 mb-12">
        @foreach ($mentors as $mentor)
            <div class="card shadow-lg rounded-lg p-6 bg-white">
                <div class="flex justify-center mb-4">
                    <img src="https://via.placeholder.com/150" alt="Mentor Image" class="rounded-full w-24 h-24" />
                </div>
                <div class="text-center mb-4">
                    <h2 class="text-xl font-bold">{{ $mentor->name }}</h2>
                    <p class="text-gray-600">Senior Developer</p>
                    <p class="text-gray-600">{{ $mentor->email }}</p>
                </div>
                <flux:button class="!bg-primary text-white! hover:!bg-primary/90" :href="route('mentor.show', $mentor->id)"></flux:button>
            </div>
        @endforeach

        {{--
            <div class="shadow-lg rounded-lg p-6 bg-white col-span-2">
            <flux:heading size="xl" class="mb-4">Goals</flux:heading>
            <div class="space-y-4">
            <div class="flex items-center">
            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mr-4">
            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 75%"></div>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-white">Finish Financial Planning: 75%</span>
            </div>
            <div class="flex items-center">
            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mr-4">
            <div class="bg-green-600 h-2.5 rounded-full" style="width: 60%"></div>
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-white">Fill in PDP survey: 60%</span>
            </div>
            </div>
            <flux:button class="!bg-primary text-white! hover:!bg-primary/90">View All Goals</flux:button>
            </div>
        --}}
    </div>
    <flux:heading size="xl" class="mb-4">Upcoming Activities</flux:heading>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-10 mb-12">
        @foreach ($activities as $activity)
            <flux:card>
                <flux:heading size="lg">{{ $activity->activity_name }}</flux:heading>
                <flux:subheading class="mb-2">Date: {{ $activity->start_date->format("M j, g:i a") }}</flux:subheading>
                <flux:subheading class="mb-2">Location: {{ $activity->address }}</flux:subheading>
                <p class="mb-4">Number of people: 20</p>
                {{-- <a href="{{route('activitie', $activity->id)}}" wire:navigate> --}}
                <flux:button variant="primary" :href="route('activity.show', $activity->id)">Join</flux:button>
                {{-- </a> --}}
            </flux:card>
        @endforeach
    </div>
    <flux:heading size="xl" class="mb-4">Upcoming Meetings</flux:heading>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-10">
        @foreach ($meetings as $meeting)
            <flux:card>
                <flux:heading size="lg">{{ $meeting->meeting_name }}</flux:heading>
                <flux:subheading class="mb-2">Date: {{ $meeting->start_date->format("M j, g:i a") }}</flux:subheading>
                <div class="flex items-center space-x-2">
                    @foreach ($meeting->attendances as $attendance)
                        <flux:text>{{ $attendance->user->name }}</flux:text>
                    @endforeach
                </div>

                <flux:subheading class="mb-2">Location: {{ $meeting->address }}</flux:subheading>
                <flux:button variant="primary" :href="route('meeting.show', $meeting->id)">Join</flux:button>
            </flux:card>
        @endforeach
    </div>
</div>
