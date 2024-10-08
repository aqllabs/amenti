<?php

use App\Models\Meeting;
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

    public Meeting $meeting;
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

    public function mount(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }
}; ?>

<div class="container mx-auto p-4 space-y-6">
    <flux:card class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <flux:heading size="lg">{{$meeting->description}}</flux:heading>
        </div>
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center">
                <flux:icon.calendar class="mr-2"/>
                <flux:subheading>{{$meeting->start_date->format('M j, g:i a')}}</flux:subheading>
            </div>
            <div class="flex items-center">
                <flux:icon.map-pin class="mr-2"/>
                <flux:subheading>{{ $meeting->address }}</flux:subheading>
            </div>
            <div class="flex items-center">
                <flux:icon.user variant="solid" class="mr-2"/>
                <flux:subheading>Dr. John</flux:subheading>
            </div>
        </div>
        <div class="flex my-6 space-x-6 items-center">
            <button class="btn bg-primary !text-white hover:bg-primary/90">
                Join Meeting
            </button>
            <button class="btn btn-error hover:bg-secondary/90">
                Cancel Attendance
            </button>
        </div>
    </flux:card>

    <flux:card class="mb-6">
        <form class="space-y-4">
            {{$this->form}}
            <button class="btn bg-primary !text-white hover:bg-primary/90">
                Submit Feedback
            </button> 
        </form>
    </flux:card>
</div>
