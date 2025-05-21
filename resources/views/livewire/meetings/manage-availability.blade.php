<?php

use App\Models\Availability;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')]
class extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('availabilities')
                    ->schema([
                        DatePicker::make('date')
                            ->required(),
                        TimePicker::make('start_time')
                            ->required()
                            ->seconds(false),
                        TimePicker::make('end_time')
                            ->required()
                            ->seconds(false)
                            ->after('start_time'),
                    ])
                    ->columns(3)
                    ->addActionLabel('Add Availability')
                    ->label('Specific Date')
                    ->reorderable(false),

            ])
            ->statePath('data');
    }

    public function submit()
    {
        $availabilities = $this->form->getState()['availabilities'];
        info($availabilities);

        foreach ($availabilities as $slot) {
            Availability::create([
                'user_id' => Auth::id(),
                'date' => $slot['date'],
                'start_time' => $slot['start_time'],
                'end_time' => $slot['end_time'],
            ]);
        }

        $this->form->fill();

        Flux::toast('Your changes have been saved.');
    }
}
?>


<div class="container mx-auto p-4 space-y-6">
    <flux:card>
        <flux:heading size="lg" class="mb-4">Manage Your Availability</flux:heading>

        <form wire:submit="submit" class="space-y-4">
            {{ $this->form }}

            <div class="flex justify-end mt-4">
                <flux:button type="submit">Save Availability</flux:button>
            </div>
        </form>
    </flux:card>
    <div class="h-fit">
        @livewire(App\Livewire\MyCalendarWidget::class, key("calendar-" . now()))
    </div>
</div>
