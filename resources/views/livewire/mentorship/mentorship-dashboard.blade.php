<?php

use Livewire\Volt\Component;
use App\Models\User;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.app')] class extends Component {
    public function with(): array
    {
        $user = Auth::user();
        $mentors = [];
        $mentees = [];
        $stats = [];

        if ($user->user_type === 'mentee') {
            $mentors = $user->mentors()->get();
            $stats = [
                'total_meetings' => $user->meetings()->count(),
                'total_activities' => $user->activities()->count(),
                'mentors_count' => $mentors->count(),
            ];
        } else {
            $mentees = $user->mentees()->get();
            $stats = [
                'total_meetings' => $user->meetings()->count(),
                'total_activities' => $user->activities()->count(),
                'mentees_count' => $mentees->count(),
            ];
        }

        return [
            'user' => $user,
            'mentors' => $mentors,
            'mentees' => $mentees,
            'stats' => $stats,
        ];
    }
}; ?>

<div class="container mx-auto p-4 space-y-6">
    <!-- Stats Section -->
    <div class="grid md:grid-cols-3 gap-4">
        <flux:card>
            <div class="text-center">
                <div class="text-3xl font-bold">{{ $stats['total_meetings'] }}</div>
                <div class="text-gray-600">Total Meetings</div>
            </div>
        </flux:card>
        <flux:card>
            <div class="text-center">
                <div class="text-3xl font-bold">{{ $stats['total_activities'] }}</div>
                <div class="text-gray-600">Total Activities</div>
            </div>
        </flux:card>
        <flux:card>
            <div class="text-center">
                <div class="text-3xl font-bold">
                    {{ $user->user_type === 'mentee' ? $stats['mentors_count'] : $stats['mentees_count'] }}
                </div>
                <div class="text-gray-600">
                    {{ $user->user_type === 'mentee' ? 'Mentors' : 'Mentees' }}
                </div>
            </div>
        </flux:card>
    </div>

    <flux:card>
        <div class="mb-6">
            <flux:heading>
                {{ $user->user_type === 'mentee' ? 'My Mentors' : 'My Mentees' }}
            </flux:heading>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach(($user->user_type === 'mentee' ? $mentors : $mentees) as $person)
                <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                    <div class="flex items-center space-x-4 mb-4">
                        <img src="{{ $person->profile_photo_url }}" 
                             alt="{{ $person->name }}" 
                             class="w-16 h-16 rounded-full">
                        <div>
                            <h3 class="font-semibold text-lg">{{ $person->name }}</h3>
                            <p class="text-gray-600">{{ ucfirst($person->user_type) }}</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <a href="{{ route('mentorship.detail', $person) }}" 
                           class="btn btn-primary text-white w-full">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </flux:card>
</div> 