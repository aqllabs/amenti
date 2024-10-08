<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Activity;

new #[Layout('layouts.app')]
class extends Component {
    public function with(): array
    {
        return [
            'activities' => Activity::paginate(10),
        ];
    }

};
?>

<div>
    <grid class="grid sm:grid-cols-2 lg:grid-cols-2 gap-10 ">
        <h1 class="text-2xl font-bold col-span-2">Activities</h1>
        @foreach ($activities as $activity )
            <flux:card class="">
            <flux:heading size="lg">{{$activity->activity_name}}</flux:heading>
                <flux:subheading class="mb-2">Date: {{$activity->start_date->format('M j, g:i a')}}</flux:subheading>
                <flux:subheading class="mb-2">Location: {{$activity->address}}</flux:subheading>
                    <flux:button class="!bg-primary !text-white hover:!bg-primary/90" :href="route('activity.show', $activity->id)">
                        View
                    </flux:button>
            </flux:card>
        @endforeach
    </grid>
</div>
