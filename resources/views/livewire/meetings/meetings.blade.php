<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Meeting;

new #[Layout("layouts.app")] class extends Component {
    public function with(): array
    {
        return [
            "meetings" => Auth::user()
                ->meetings()
                ->paginate(10),
        ];
    }
};
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-gray-800">Upcoming Meetings</h1>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($meetings as $meeting)
            <flux:card class="space-y-6">
                <div class="">
                    <div class="flex items-center mb-4">
                        <flux:icon name="calendar" class="w-5 h-5 text-gray-500 mr-2" />
                        <flux:subheading>{{ $meeting->start_date->format("M d, Y") }}</flux:subheading>
                        <flux:badge class="ml-2" color="green">{{ $meeting->start_date->format("H:i") }}</flux:badge>
                    </div>
                    <div class="flex items-center mb-4">
                        <flux:icon name="map-pin" class="w-5 h-5 text-gray-500 mr-2" />
                        <flux:subheading>{{ $meeting->address }}</flux:subheading>
                    </div>
                </div>
                <flux:subheading>{{ Str::limit($meeting->description, 100) }}</flux:subheading>
                <div class="flex justify-end">
                    <flux:button icon="arrow-right" variant="primary" :href="route('meeting.show', $meeting->id)">Join Meeting</flux:button>
                </div>
            </flux:card>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 text-lg">No upcoming meetings scheduled.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $meetings->links() }}
    </div>
</div>
