<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Availability;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Flux\Flux;

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
                        // Replace TimePicker components with a CheckboxList
                        \Filament\Forms\Components\CheckboxList::make('time_slots')
                            ->options(function () {
                                $slots = [];
                                for ($hour = 8; $hour < 22; $hour++) {
                                    $time = sprintf('%02d:00', $hour);
                                    $slots[$time] = $time . ' - ' . sprintf('%02d:00', $hour + 1);
                                }
                                return $slots;
                            })
                            ->columns(3)
                            ->required()
                    ])
                    ->columns(2)
                    ->addActionLabel('Add Availability')
                    ->label('Availability Slots')
                    ->reorderable(false),
            ])
            ->statePath('data');
    }

    public function submit()
    {
        $availabilities = $this->form->getState()['availabilities'];
        info($availabilities);

        foreach ($availabilities as $slot) {
            foreach ($slot['time_slots'] as $time) {
                $start_time = $slot['date'] . ' ' . $time;
                info($start_time);

                $end_time = date('Y-m-d H:i:s', strtotime($start_time . ' +1 hour'));

                info($start_time . ' - ' . $end_time);
                Availability::create([
                    'user_id' => auth()->id(),
                    'date' => $slot['date'],
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                ]);
            }
        }

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
                <flux:button type="submit" class="!bg-primary !text-white hover:!bg-primary/90">
                    Save Availability
                </flux:button>
            </div>
        </form>
    </flux:card>
    <div class="h-full">
        @livewire(App\Livewire\MyCalendarWidget::class)
    </div>

</div>
