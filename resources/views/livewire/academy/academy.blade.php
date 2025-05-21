<?php

use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;
use App\Models\Course;
use Livewire\Attributes\Layout;

new #[Layout("layouts.app")] class extends Component {
    public $progressGroupedByCourse;

    public function mount()
    {
        $courseProgresses = auth()
            ->user()
            ->completedLessons()
            ->get();
        $this->progressGroupedByCourse = $courseProgresses->groupBy("course_id")->map(function ($group) {
            return $group->count();
        });
    }

    public function with(): array
    {
        return [
            "courses" => Course::paginate(10),
        ];
    }
}; ?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <h1 class="text-2xl font-bold col-span-2 mb-6">Academy</h1>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach ($courses as $course)
            <flux:card :title="$course->title" class="flex flex-col">
                <flux.heading size="lg">{{ $course->title }}</flux.heading>

                <div class="flex items-center mt-2">
                    <flux:icon.book-open variant="micro" class="text-gray-500 mr-2" />
                    <flux:subheading class="text-sm">{{ $progressGroupedByCourse->get($course->id) ?? 0 }} / {{ $course->lessons()->count() }} lessons completed</flux:subheading>
                </div>
                <progress
                    value="{{ (($progressGroupedByCourse->get($course->id) ?? 0) / $course->lessons()->count()) * 100 }}"
                    max="100"
                    class="progress progress-primary w-full mt-2 mb-4"
                ></progress>
                <div class="mt-auto">
                    <flux:button :href="route('academy.course.show', $course)" variant="primary" class="!bg-primary !text-white hover:!bg-primary/90 w-full">
                        Start Course
                    </flux:button>
                </div>
            </flux:card>
        @endforeach
    </div>
</div>
