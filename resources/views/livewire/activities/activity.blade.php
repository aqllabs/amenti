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
        <div class="flex justify-between items-center mb-4">
            <flux:heading size="lg">{{$activity->activity_name}}</flux:heading>
        </div>
        <p class="mb-4">{{ $activity->description }}</p>
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <flux:icon.calendar class="mr-2"/>
                <flux:subheading>{{$activity->start_date->format('M j, g:i a')}}</flux:subheading>
            </div>
            <div class="flex items-center">
                <flux:icon.map-pin class="mr-2"/>
                <flux:subheading>{{ $activity->address }}</flux:subheading>
            </div>
        </div>
         <div class="flex my-6 space-x-6 items-center mb-4">
            <button label="Join" class="btn bg-primary !text-white hover:bg-primary/90">
                Join
            </button>
            <button label="Cannot join" class="btn btn-error hover:bg-secondary/90">
                Cannot join
            </button>
        </div>
    </flux:card>

    <flux:card class="mb-6">
        <div class="flex items-center mb-4">
            <flux:icon.users variant="solid" class="mr-2"/>
            <flux:heading size="xl">Attendees</flux:heading>
        </div>
        <ul class="space-y-2">
            <li>John Doe</li>
            <li>Jane Smith</li>
            <li>Michael Johnson</li>
            <li>Emily Davis</li>
        </ul>
        
    </flux:card>


    <flux:card class="mb-6">
        <form class="space-y-4">
            {{$this->form}}
            <button label="Submit" class="btn bg-primary !text-white hover:bg-primary/90">
                Submit
            </button> 
        </form>
    </flux:card>
</div>
