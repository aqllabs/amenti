<?php

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Activity;
use Filament\Forms\Contracts\HasForms;

new #[Layout('layouts.app')]
class extends Component implements HasForms {

    public Activity $activity;
    public bool $showFeedbackForm = false;

    use InteractsWithForms;

    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('user_feedback')->label('Feedback'),
                Select::make('rating')->options([
                    1 => '1',
                    2 => '2',
                    3 => '3',
                    4 => '4',
                    5 => '5',
                ])->label('Rating'),
            ])
            ->statePath('data');
    }


    public function mount(Activity $activity)
    {
        $this->activity = $activity;
    }
}; ?>

<div class="container mx-auto p-4 space-y-6">
    <flux:card class="mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <flux:heading size="lg">{{$activity->activity_name}}</flux:heading>
                <p class="text-gray-600 mt-2">{{ $activity->description }}</p>
            </div>
            <div class="px-4 py-2 rounded-full bg-gray-100">
                Your Status: Not Responded
            </div>
        </div>

        @if($activity->image_url)
            <div class="mb-6">
                <img src="{{ $activity->image_url }}" 
                     alt="{{ $activity->activity_name }}" 
                     class="w-full h-64 object-cover rounded-lg">
            </div>
        @endif

        <div class="grid md:grid-cols-2 gap-4 mb-6">
            <div class="flex items-center">
                <flux:icon.calendar class="mr-2 text-primary"/>
                <div>
                    <div class="text-sm text-gray-600">Date & Time</div>
                    <flux:subheading>{{$activity->start_date->format('M j, g:i a')}}</flux:subheading>
                </div>
            </div>
            <div class="flex items-center">
                <flux:icon.map-pin class="mr-2 text-primary"/>
                <div>
                    <div class="text-sm text-gray-600">Location</div>
                    <flux:subheading>{{ $activity->address }}</flux:subheading>
                </div>
            </div>
        </div>

        <div class="border-t border-b py-6 my-6">
            <div class="text-center mb-4">
                <flux:heading >Will you join this activity?</flux:heading>
                <p class="text-gray-600">12 spots remaining</p>
            </div>
            <div class="flex justify-center space-x-4">
                <button wire:click="rsvpYes" class="btn bg-primary !text-white hover:bg-primary/90 min-w-[120px]">
                    <flux:icon.check class="mr-2"/> I'll Attend
                </button>
                <button wire:click="rsvpNo" class="btn border-2 border-gray-300 hover:bg-gray-50 min-w-[120px]">
                    <flux:icon.x-mark class="mr-2"/> Decline
                </button>
            </div>
        </div>
    </flux:card>

    <flux:card class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <flux:icon.users variant="solid" class="mr-2"/>
                <flux:heading >Attendees (4)</flux:heading>
            </div>
            <div class="text-sm text-gray-600">
                Max Capacity: 16
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach(['John Doe', 'Jane Smith', 'Michael Johnson', 'Emily Davis'] as $attendee)
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-gray-200 mr-2"></div>
                    <span>{{ $attendee }}</span>
                </div>
            @endforeach
        </div>
    </flux:card>

    @if($showFeedbackForm)
        <flux:card class="mb-6">
            <flux:heading size="sm" class="mb-4">Share Your Feedback</flux:heading>
            <form wire:submit.prevent="submitFeedback" class="space-y-4">
                {{$this->form}}
                <button type="submit" class="btn bg-primary !text-white hover:bg-primary/90">
                    Submit Feedback
                </button> 
            </form>
        </flux:card>
    @endif
</div>
