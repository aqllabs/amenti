<?php

use Filament\Forms\Components\Radio;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\MyForm;
use App\Models\FormSubmission;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Illuminate\Contracts\View\View;


new #[Layout('layouts.app')]
class extends Component implements HasForms {
    public MyForm $myForm;

    public bool $isFilled = false;

    use InteractsWithForms;

    public ?array $data = [];

    public function mount(MyForm $myForm): void
    {
        $this->myForm = $myForm;
        $submission = auth()->user()->formSubmissions()->where('form_id', $myForm->id)->orderBy('created_at', 'desc')->first();
        $this->isFilled = $submission != [] || null;
        $this->form->fill($submission->responses ?? []);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                self::createFormSchema($this->myForm->structure)
            )->statePath('data')->disabled($this->isFilled);
    }

    public function create(): void
    {
        $newForm = [
            'responses' => $this->form->getState(),
            'form_id' => $this->myForm->id,
            'user_id' => auth()->id()
        ];
        MyForm::find($this->myForm->id)->formSubmissions()->create($newForm);

        redirect()->route('forms');
    }

    private static function createFormSchema(
        $structure,
    )
    {
        return collect($structure)->map(function ($block) {

            return match ($block['type']) {
                'short_answer' => TextInput::make($block['data']['id'])
                    ->label($block['data']['question']),
                'long_answer' => \Filament\Forms\Components\Textarea::make($block['data']['id'])
                    ->label($block['data']['question']),
                'checkboxes' => CheckboxList::make($block['data']['id'])
                    ->options(array_combine($block['data']['options'], $block['data']['options']))
                    ->label($block['data']['question']),
                'select' => Select::make($block['data']['id'])
                    ->options(array_combine($block['data']['options'], $block['data']['options']))
                    ->label($block['data']['question']),
                'multiple_choice' => Radio::make($block['data']['id'])
                    ->options(array_combine($block['data']['options'], $block['data']['options']))
                    ->label($block['data']['question']),
                default => null,
            };
        })->toArray();
    }
}; ?>

<div>
    {{-- parse json structure to string --}}
    <flux:card class="space-y-6">
        <flux:heading size="lg">{{ $myForm->title }} Form</flux:heading>
        <flux:subheading class="text-white">{{ $myForm->description }}</flux:subheading>
        <form wire:submit="create" class="text-black space-y-6">
            {{ $this->form }}
            @if(!$isFilled)
                <flux:button type="submit">
                    Submit
                </flux:button>
            @endif
        </form>
    </flux:card>
</div>
