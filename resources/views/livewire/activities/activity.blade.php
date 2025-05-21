<?php

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Activity;
use App\Models\ActivityAttendance;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.app')] 
class extends Component implements HasForms {
    use InteractsWithForms;

    public Activity $activity;
    public bool $showFeedbackForm = false;
    public ?array $data = [];
    public ?string $userStatus = null;

    public function mount(Activity $activity)
    {
        $this->activity = $activity;
        $this->loadUserStatus();
    }

    public function loadUserStatus()
    {
        $attendance = $this->activity->attendances()
            ->where('user_id', Auth::id())
            ->first();
        
        $this->userStatus = $attendance ? $attendance->status : 'Not Responded';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('user_feedback')
                    ->label('Feedback')
                    ->required(),
                Select::make('rating')
                    ->options([
                        1 => '1 - Poor',
                        2 => '2 - Fair',
                        3 => '3 - Good',
                        4 => '4 - Very Good',
                        5 => '5 - Excellent',
                    ])
                    ->label('Rating')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function rsvpYes()
    {
        $this->activity->attendances()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['status' => 'ACCEPTED']
        );
        $this->loadUserStatus();
    }

    public function rsvpNo()
    {
        $this->activity->attendances()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['status' => 'REJECTED']
        );
        $this->loadUserStatus();
    }

    public function submitFeedback()
    {
        $this->activity->attendances()
            ->where('user_id', Auth::id())
            ->update([
                'feedback' => $this->data['user_feedback'],
                'rating' => $this->data['rating']
            ]);

        $this->showFeedbackForm = false;
        $this->dispatch('feedback-submitted');
    }
}; ?>

<div class="container mx-auto p-4 space-y-6">
    <flux:card class="mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <flux:heading size="lg">{{$activity->activity_name}}</flux:heading>
                <p class="text-gray-600 mt-2">{{ $activity->description }}</p>
            </div>
            <flux:badge class="px-4 py-2 rounded-full" 
            color="{{ $userStatus === 'ACCEPTED' ? 'green' : 
                ($userStatus === 'REJECTED' ? 'red' : 
                ($userStatus === 'ATTENDED' ? 'blue' : 
                ($userStatus === 'NOSHOW' ? 'yellow' : 'gray'))) }}"
            >
                Status: {{ $userStatus }}
            </flux:badge>
        </div>

        @if($activity->image_urls)
            <div class="mb-6">
                <img src="{{ $activity->image_urls }}" 
                     alt="{{ $activity->activity_name }}" 
                     class="w-full h-64 object-cover rounded-lg">
            </div>
        @endif

        <div class="grid md:grid-cols-3 grid-cols-1 gap-4 mb-6 w-full">
            <div class="flex items-center justify-center">
                <flux:icon.calendar class="mr-2 text-primary"/>
                <div>
                    <div class="text-sm text-gray-600">Date & Time</div>
                    <flux:subheading>{{$activity->start_date->format('M j, g:i a')}}</flux:subheading>
                </div>
            </div>
            <div class="flex items-center justify-center">
                <flux:icon.map-pin class="mr-2 text-primary"/>
                <div>
                    <div class="text-sm text-gray-600">Location</div>
                    <flux:subheading>{{ $activity->address }}</flux:subheading>
                </div>
            </div>
            <div class="flex items-center justify-center">
                <flux:icon.clock class="mr-2 text-primary"/>
                <div>
                    <div class="text-sm text-gray-600">Duration</div>
                    <flux:subheading>{{ $activity->duration }} hours</flux:subheading>
                </div>
            </div>
        </div>

        @if($userStatus === 'ACCEPTED')
                <div class="text-center mb-4">
                    <flux:heading>Change your response?</flux:heading>
                </div>
                <div class="flex justify-center">
                    <flux:button icon="x-mark" variant="danger" wire:click="rsvpNo">
                        Cancel my attendance
                    </flux:button>
                </div>
        @elseif($userStatus === 'REJECTED')
                <div class="text-center mb-4">
                    <flux:heading>Change your response?</flux:heading>
                </div>
                <div class="flex justify-center">
                    <flux:button icon="check" variant="primary" wire:click="rsvpYes">
                        I'll Attend
                    </flux:button>
                </div>
        @elseif($userStatus !== 'ATTENDED' && $userStatus !== 'NOSHOW')
                <div class="text-center mb-4">
                    <flux:heading>Will you join this activity?</flux:heading>
                </div>
                <div class="flex justify-center space-x-4">
                    <flux:button icon="check" wire:click="rsvpYes" variant="primary">
                        I'll Attend
                    </flux:button>
                    <flux:button icon="x-mark" wire:click="rsvpNo" variant="danger">
                        Decline
                    </flux:button>
                </div>
        @endif
    </flux:card>

    <flux:card class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <flux:icon.users variant="solid" class="mr-2"/>
                <flux:heading>Attendees ({{ $activity->attendees()->whereIn('status', ['ACCEPTED', 'ATTENDED'])->count() }})</flux:heading>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($activity->attendees()->whereIn('status', ['ACCEPTED', 'ATTENDED'])->get() as $attendee)
                <div class="flex items-center">
                    <img src="{{ $attendee->profile_photo_url }}" 
                         class="w-8 h-8 rounded-full mr-2"
                         alt="{{ $attendee->name }}">
                    <span>{{ $attendee->name }}</span>
                </div>
            @endforeach
        </div>
    </flux:card>

    @if($showFeedbackForm && $userStatus === 'ACCEPTED')
        <flux:card class="mb-6">
            <flux:heading size="lg" class="mb-4">Share Your Feedback</flux:heading>
            <form wire:submit="submitFeedback" class="space-y-4">
                {{ $this->form }}
                <flux:button type="submit" variant="primary">
                    Submit Feedback
                </flux:button> 
            </form>
        </flux:card>
    @endif
</div>
