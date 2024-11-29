<?php

use Livewire\Volt\Component;
use App\Models\User;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.app')] class extends Component {
    public User $targetUser;
    
    public function mount(User $user)
    {
        $this->targetUser = $user;
        
        // Security check - ensure current user has relationship with target user
        $currentUser = Auth::user();
        $hasRelationship = false;
        
        if ($currentUser->user_type === 'mentee') {
            $hasRelationship = $currentUser->mentors()->where('users.id', $user->id)->exists();
        } else {
            $hasRelationship = $currentUser->mentees()->where('users.id', $user->id)->exists();
        }
        
        if (!$hasRelationship) {
            abort(403);
        }
    }

    public function with(): array
    {
        return [
            'activities' => $this->targetUser->activities()
                ->orderBy('start_date', 'desc')
                ->take(5)
                ->get(),
            'meetings' => $this->targetUser->meetings()
                ->orderBy('start_date', 'desc')
                ->take(5)
                ->get(),
            'relationship' => $this->targetUser->user_type === 'mentor' ? 'Mentor' : 'Mentee',
            'relationshipStartDate' => $this->getRelationshipStartDate(),
        ];
    }

    private function getRelationshipStartDate()
    {
        $currentUser = Auth::user();
        $mentorship = $currentUser->user_type === 'mentee' 
            ? $currentUser->mentors()->where('users.id', $this->targetUser->id)->first()->pivot
            : $currentUser->mentees()->where('users.id', $this->targetUser->id)->first()->pivot;
        
        return $mentorship?->created_at;
    }
}; ?>

<div class="container mx-auto p-4 space-y-6">
    <flux:card>
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center space-x-4">
                <img src="{{ $targetUser->profile_photo_url }}" 
                     alt="{{ $targetUser->name }}" 
                     class="w-20 h-20 rounded-full">
                <div>
                    <flux:heading>{{ $targetUser->name }}</flux:heading>
                    <p class="text-gray-600">Your {{ $relationship }}</p>
                    @if($relationshipStartDate)
                        <p class="text-sm text-gray-500">
                            Mentoring since {{ $relationshipStartDate->format('M Y') }}
                            ({{ $relationshipStartDate->diffForHumans() }})
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <flux:heading  class="mb-4">Contact Information</flux:heading>
                <div class="space-y-2">
                    <p><span class="font-semibold">Email:</span> {{ $targetUser->email }}</p>
                </div>
            </div>
        </div>
    </flux:card>

    <flux:card>
        <flux:heading  class="mb-4">Recent Activities</flux:heading>
        <div class="space-y-4">
            @forelse($activities as $activity)
                <div class="border-b last:border-b-0 pb-4 last:pb-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-semibold">{{ $activity->activity_name }}</h4>
                            <p class="text-sm text-gray-600">
                                {{ $activity->start_date->format('M j, Y g:i A') }}
                            </p>
                        </div>
                        <span class="px-2 py-1 text-sm rounded-full {{ 
                            $activity->pivot->status === 'ATTENDED' ? 'bg-green-100 text-green-800' : 
                            ($activity->pivot->status === 'ACCEPTED' ? 'bg-blue-100 text-blue-800' : 
                            'bg-gray-100 text-gray-800') 
                        }}">
                            {{ $activity->pivot->status }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-gray-600">No recent activities</p>
            @endforelse
        </div>
    </flux:card>

    <flux:card>
        <flux:heading class="mb-4">Recent Meetings</flux:heading>
        <div class="space-y-4">
            @forelse($meetings as $meeting)
                <div class="border-b last:border-b-0 pb-4 last:pb-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-semibold">{{ $meeting->title }}</h4>
                            <p class="text-sm text-gray-600">
                                {{ $meeting->start_date->format('M j, Y g:i A') }}
                            </p>
                        </div>
                        <span class="px-2 py-1 text-sm rounded-full {{ 
                            $meeting->pivot->status === 'ATTENDED' ? 'bg-green-100 text-green-800' : 
                            ($meeting->pivot->status === 'ACCEPTED' ? 'bg-blue-100 text-blue-800' : 
                            'bg-gray-100 text-gray-800') 
                        }}">
                            {{ $meeting->pivot->status }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-gray-600">No recent meetings</p>
            @endforelse
        </div>
    </flux:card>
</div> 