<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\MyForm;

new #[Layout('layouts.app')]
class extends Component
{

    public function with(): array
    {

        return [
            'notSubmittedForms' => MyForm::whereDoesntHave('formSubmissions')->get(),
            'submittedForms' => auth()->user()->submittedForms()->distinct('form_submissions.form_id')->get()
        ];
    }


};
?>

<div>
    <grid class="grid sm:grid-cols-2 lg:grid-cols-2 gap-6  mb-6 ">
        <flux:heading size="xl" class="col-span-2">Waiting Forms</flux:heading>
        @foreach ($notSubmittedForms as $aForm )
            <flux:card class="flex flex-col space-y-6">
                <flux:heading size="lg">{{$aForm->title}}</flux:heading>
                <flux:subheading size="sm">{{$aForm->description}}</flux:subheading>
                <flux:button :href="route('form.show', $aForm)"  class="!btn !btn-primary !text-white w-full">Fill Form</flux:button>
            </flux:card>
        @endforeach
        @if (count($notSubmittedForms) == 0)
            Nothing to show yet
        @endif
    </grid>

    <grid class="grid sm:grid-cols-2 lg:grid-cols-2 gap-10 ">
        <h1 class="text-2xl font-bold col-span-2">Submitted Forms</h1>
        @if (count($submittedForms) == 0)
            Nothing to show yet
        @endif
        @foreach ($submittedForms as $myForm )
        <flux:card class="flex flex-col space-y-2">
            <flux:heading size="lg">{{$myForm->title}}</flux:heading>
            <flux:subheading size="sm">{{$myForm->description}}</flux:subheading>
             <div class="flex items-center gap-2">
                <flux:icon.check-circle variant="micro" class="text-green-600 dark:text-green-500" />
                <span class="text-sm text-green-600 dark:text-green-500">Submitted on {{$myForm->pivot->created_at->toDateString()}}</span>
            </div>
            <flux:button :href="route('form.show', $myForm)"  class="!btn !btn-primary !text-white w-full">View Form</flux:button>
        </flux:card>
        @endforeach
    </grid>

</div>
