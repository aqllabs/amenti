<?php

use App\Models\Course;
use App\Models\Quiz;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Lesson;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;


new #[Layout('layouts.app')]
class extends Component implements HasForms {

    use InteractsWithForms;

    public ?array $data = [];


    public Lesson $lesson;
    public Course $course;
    public Lesson|null $nextLesson;
    public Lesson|null $previousLesson;
    public bool $isEnrolled = false;

    public Quiz $quiz;
    public bool $isCompleted = false;

    public bool $isAnswered = false;


    #[On('quizSubmitted')]
    public function refresh()
    {
    }

    public function mount(Course $course, Lesson $lesson)
    {
        $this->lesson = $lesson;
        $this->course = $course;
        $this->isEnrolled = auth()->user()->userEnrollments()->where('course_id', $course->id)->exists();

        // Find next and previous lessons

        $orderedLessons = $this->course->lessons->sortBy('order');

// Find the current lesson's index
        $currentIndex = $orderedLessons->search(function ($item) {
            return $item->id === $this->lesson->id;
        });

// Find next lesson
        $this->nextLesson = $orderedLessons->get($currentIndex + 1);

// Find previous lesson
        $this->previousLesson = $orderedLessons->get($currentIndex - 1);

        // Check if the quiz exists
        $this->quiz = $lesson->quiz;

        if ($this->quiz->exists) {
            $submission = auth()->user()->quizSubmissions()->where('quiz_id', $this->quiz->id)->first();
            $this->isAnswered = !is_null($submission);
            $this->form->fill($submission ? $submission->responses : []);
        } else {
            $this->isAnswered = false;
        }

        $this->isCompleted = auth()->user()->completedLessons()->where('lesson_id', $lesson->id)->exists();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                self::createFormSchema($this->quiz->structure ?? [], $this->isAnswered)
            )->statePath('data')->disabled($this->isAnswered);

    }


    private static function createFormSchema($structure, $isAnswered)
    {
        return collect($structure)->map(function ($block) use ($isAnswered) {

            if ($block['type'] == 'multiple_choice') {
                $options = collect($block['data']['options'])->map(function ($option) {
                    return $option['option'];
                })->toArray();

                $correctAnswer = collect($block['data']['options'])
                    ->filter(function ($option) {
                        return $option['is_correct'];
                    })
                    ->pluck('option')
                    ->first();
            } elseif ($block['type'] == 'short_answer') {
                $options = [];
                $correctAnswer = $block['data']['answer'];
            } elseif ($block['type'] == 'long_answer') {
                $options = [];
                $correctAnswer = $block['data']['answer'];
            }

            return match ($block['type']) {
                'multiple_choice' => Radio::make($block['data']['id'])
                    ->options(array_combine($options, $options))
                    ->label($block['data']['question'])
                    ->live()
                    ->helperText(($isAnswered && $correctAnswer) ? new HtmlString("The correct answer is: <strong>$correctAnswer</strong>") : ''),
                'short_answer' => TextInput::make(
                    $block['data']['id']
                )->label($block['data']['question'])
                    ->helperText(($isAnswered && $correctAnswer) ? new HtmlString("The correct answer is: <strong>$correctAnswer</strong>") : ''),
                'long_answer' => Textarea::make(
                    $block['data']['id']
                )->label($block['data']['question'])
                    ->helperText(($isAnswered && $correctAnswer) ? new HtmlString("The correct answer is: <strong>$correctAnswer</strong>") : ''),
                default => null
            };
        })->toArray();
    }

    public function submitQuiz(): void
    {
        if (!$this->isEnrolled) {
            $this->js("alert('Please enroll to the course first')");
            return;
        }
        $newQuizResponse = [
            'responses' => $this->form->getState(),
            'quiz_id' => $this->quiz->id,
            'user_id' => auth()->id()
        ];

        $submission = $this->quiz->quizSubmission()->create($newQuizResponse);
        $this->isAnswered = true;
        $this->form->fill($submission->responses ?? []);
        $this->dispatch("quizSubmitted");
    }

    public function done(): void
    {
        $this->isCompleted = auth()->user()->finishLesson($this->lesson);
        if (!$this->isCompleted) {
            $this->js("alert('Please enroll to the course first')");
        }
    }


}; ?>

<div class="space-y-6">
    <a href='{{route("academy.course.show", $course)}}' class="link link-primary">< Back</a>
    <div class="my-6">
        <div class="flex justify-between items-center mb-4">
                <flux:button wire:navigate icon="arrow-left" :class="$previousLesson ? 'opacity-100' : 'opacity-0'"
                               :href='$previousLesson ? route("academy.lesson.show", [$course, $previousLesson]) : "#"'></flux:button.arrow-left>
            <h2 class="text-2xl font-semibold">{{$lesson->title}}</h2>

            @if($nextLesson)
                <flux:button wire:navigate icon="arrow-right"
                               :href='route("academy.lesson.show", [$course, $nextLesson])'></flux:button.arrow-right>
            @else
                <div></div>
            @endif
        </div>
        <div class="flex justify-center items-center mb-4">
            @if(Str::startsWith($lesson->video_url, 'https://'))
                <div class="w-full max-w-3xl  aspect-w-16 aspect-h-9">
                    <iframe class="w-full h-full md:min-h-96"
                            src="{{$lesson->video_url}}" title="YouTube video player"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            @endif
        </div>
        <div class="flex justify-center items-center">
            @if($isCompleted)
                <flux:badge  class="badge-primary text-white">Completed</flux:badge>
            @else
                <flux:button class="btn-success text-white" wire:click="done">Done</flux:button>
            @endif
        </div>
        <div class="prose prose-md px-2 mt-2">
            {!! $lesson->content !!}
        </div>
    </div>
    @if($quiz->structure)
        <div class="mb-6">
            <h2 class="text-2xl font-semibold mb-4">Quiz</h2>
            <form wire:submit="submitQuiz" class="text-black ">
                {{ $this->form }}
                @if(!$isAnswered)
                    <flux:button type="submit">
                        Submit
                    </flux:button>
                @endif
            </form>

        </div>
    @endif

</div>
