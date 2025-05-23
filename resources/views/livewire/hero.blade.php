<div class="hero min-h-screen bg-base-100">
    <div class="hero-content flex-col lg:flex-row">
        <div class="basis-2/3">
            <h1 class="text-5xl sm:text-7xl font-bold">
                {{ __('Empower Mentorship with') }} <span class="text-secondary">{{ __('MentorHub') }}</span></h1>
            <p class="mt-6">
                {{ __('MentorHub is a comprehensive mentorship program management platform. Seamlessly manage mentor, mentee, and parent accounts, organize meetings and activities, track attendance, generate reports, and more - all in one place.') }}
            </p>
            <a href="/" class="btn btn-secondary btn-widest text-lg text-white mt-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z"/>
                </svg>
                {{ __('Get Started') }}
            </a>
            {{-- <livewire:ratings/> --}}
        </div>
        <div class="basis-1/3">
            <img src="https://placehold.co/400" class="rounded-lg" alt=""/>
        </div>
    </div>
</div>
