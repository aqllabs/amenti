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

new #[Layout("layouts.app")] class extends Component implements HasForms {
    public Meeting $meeting;
    public $attendance;
    use InteractsWithForms;

    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make("user_feedback")->label("Feedback"),
                Select::make("rating")
                    ->options([
                        1 => "1",
                        2 => "2",
                        3 => "3",
                        4 => "4",
                        5 => "5",
                    ])
                    ->label("Rating"),
            ])
            ->statePath("data");
    }

    public function mount(Meeting $meeting)
    {
        $this->meeting = $meeting;
        $this->attendance = $meeting
            ->attendances()
            ->where("user_id", auth()->id())
            ->first();
        $this->data = [
            "rating" => $this->attendance?->rating,
            "user_feedback" => $this->attendance?->feedback,
        ];
    }

    public function updateAttendance($status)
    {
        $this->meeting->attendances()->updateOrCreate(["user_id" => auth()->id()], ["status" => $status]);
        $this->attendance = $this->meeting
            ->attendances()
            ->where("user_id", auth()->id())
            ->first();
    }

    public function submitFeedback()
    {
        $this->meeting->attendances()->updateOrCreate(
            ["user_id" => auth()->id()],
            [
                "rating" => $this->data["rating"],
                "feedback" => $this->data["user_feedback"],
            ],
        );
    }
}; ?>

<div class="container mx-auto p-4 space-y-6">
    <flux:card>
        <div class="flex justify-between items-center mb-6">
            <flux:heading size="lg">{{ $meeting->description }}</flux:heading>
            <flux:badge class="px-4 py-2 rounded-full">Status: {{ $attendance?->status ?? "Not Responded" }}</flux:badge>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="flex items-center">
                <flux:icon.calendar class="mr-2" />
                <flux:subheading>{{ $meeting->start_date->format("M j, g:i a") }}</flux:subheading>
            </div>
            <div class="flex items-center">
                <flux:icon.map-pin class="mr-2" />
                <flux:subheading>{{ $meeting->address }}</flux:subheading>
            </div>
            <div class="flex items-center">
                <flux:icon.camera class="mr-2" />
                <flux:subheading>Format: {{ $meeting->format }}</flux:subheading>
            </div>
            <div class="flex items-center">
                <flux:icon.user variant="solid" class="mr-2" />
                <flux:subheading>Host: {{ $meeting->createdBy?->name }}</flux:subheading>
            </div>
        </div>

        <div class="flex space-x-4">
            @if (! $attendance || in_array($attendance->status, ["INVITED", "REJECTED"]))
                <flux:button wire:click="updateAttendance('ACCEPTED')" variant="primary">Accept Invitation</flux:button>
            @endif

            @if ($attendance?->status === "ACCEPTED")
                @if ($meeting->format === "ONLINE")
                    <flux:button href="#" variant="primary">Join Meeting</flux:button>
                @endif

                <flux:button wire:click="updateAttendance('REJECTED')" variant="danger">Cancel Attendance</flux:button>
            @endif
        </div>
    </flux:card>

    @if ($attendance?->status === "ATTENDED")
        <flux:card>
            <form wire:submit="submitFeedback" class="space-y-4">
                {{ $this->form }}
                <flux:button type="submit" variant="primary">Submit Feedback</flux:button>
            </form>
        </flux:card>
    @endif
</div>
