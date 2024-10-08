<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;


new #[Layout('layouts.app')]
class extends Component {

    public Course $course;

    public bool $isCompleted = false;
    public bool $isEnrolled;
    public float $courseProgress = 0;
    public float $totalLessons = 0;

    public function mount(Course $course)
    {
        $this->course = $course;
        if (Auth::check()) {
            $this->isEnrolled = Auth::user()->userEnrollments()->where('course_id', $course->id)->exists();
            $this->courseProgress = Auth::user()->getCourseProgress($course->id);
            $this->totalLessons = $this->course->lessons()->count();
        }
    }


    public function enroll(): void
    {
        if ($this->isEnrolled) {
            return;
        }
        Auth::user()->userEnrollments()->attach($this->course->id);
        $this->isEnrolled = true;
        $this->redirectRoute('academy.lesson.show', [$this->course, $this->course->lessons()->first()]);
    }

    public bool $show = false;


}; ?>

<div class="container mx-auto p-4 space-y-6">
    <flux:card class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">{{$course->title}}</h2>
        </div>
        <p class="mb-4">{{ $course->description }}</p>
        <div class="flex justify-between items-center">
            <div>
{{--                <x-mary-icon name="o-book-open" class="mr-2"/>--}}
                <span>{{$course->lessons()->get()->count()}} Lesson(s)</span>
            </div>
        </div>
    </flux:card>

    <!-- Course Progress -->
    <flux:card class="space-y-6">
        <div class="flex items-center">
            <flux:icon.book-open variant="solid" class="mr-2"/>
            <flux:heading size="xl">Your Progress</flux:heading>
        </div>
            <progress value="{{ $courseProgress/ $totalLessons * 100 }}" max="100" class="progress progress-primary mb-2 w-full"></progress>
        <div class="flex justify-between">
            @if($isEnrolled)
                <flux:button :href="route('academy.lesson.show', [$course, $course->lessons()->first()])">Continue Course
                </flux:button>
            @else
                <flux:button wire:click="enroll">Enroll Now</flux:button>
        @endif
        </div>
    </flux:card>

    {{-- collapse in alpine --}}
    <flux:card class="mb-6">
        <h2 class="text-2xl font-semibold mb-4">Lessons</h2>
        <div>
            @foreach ($course->lessons as $index => $lesson)
                <div class="mb-4">
                    <a class="link link-secondary	 font-semibold"
                       :href="route('academy.lesson.show', [$course, $lesson]) ">{{ $lesson->title }}</a>
                </div>
            @endforeach
        </div>
    </flux:card>

    <!-- Additional Resources -->

    <flux:card class="mb-6 space-y-6">
        <h2 class="text-2xl font-semibold mb-4">Additional Resources</h2>
        <div class="flex items-center">
            <flux:icon.document-text class="mr-2"/>
            <a href="#" class="text-blue-500 hover:underline">Course Syllabus</a>
        </div>
        <div class="flex items-center">
            <flux:icon.document-text name="o-book-open" class="mr-2"/>
            <a href="#" class="text-blue-500 hover:underline">Recommended Readings</a>
        </div>
    </flux:card>
</div>
