@php use Filament\Facades\Filament; @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!--flux fonts-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..600&display=swap" rel="stylesheet">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        @fluxStyles
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <flux:brand href="#" logo="images/logo.svg" name="Mentorship HK" class="px-2 dark:hidden" />
            <flux:brand href="#" logo="images/logo.svg" name="Mentorship HK" class="px-2 hidden dark:flex" />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="home" wire:navigate href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')">Home</flux:navlist.item>
                <flux:navlist.item icon="calendar" wire:navigate href="{{ route('activities.index') }}" :current="request()->routeIs('activities.index')">Activities</flux:navlist.item>
                <flux:navlist.item icon="chat-bubble-left-right" wire:navigate href="{{ route('meetings') }}" :current="request()->routeIs('meetings')">Meetings</flux:navlist.item>
                <flux:navlist.item icon="document-text" wire:navigate href="{{ route('forms') }}" :current="request()->routeIs('forms')">Forms</flux:navlist.item>
                <flux:navlist.item icon="star" wire:navigate href="{{ route('academy.index') }}" :current="request()->routeIs('academy.*')">Academy</flux:navlist.item>
                <flux:navlist.item icon="sparkles" wire:navigate href="{{ route('ai-chat') }}" :current="request()->routeIs('ai-chat')">AI</flux:navlist.item>


                {{-- <flux:navlist.group expandable heading="Favorites" class="hidden lg:grid">
                    <flux:navlist.item href="#">Marketing site</flux:navlist.item>
                    <flux:navlist.item href="#">Android app</flux:navlist.item>
                    <flux:navlist.item href="#">Brand guidelines</flux:navlist.item>
                </flux:navlist.group> --}}
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="information-circle" href="#">Help</flux:navlist.item>
            </flux:navlist>

            @if (Laravel\Jetstream\Jetstream::hasTeamFeatures() && Auth::user()->isMemberOfATeam())
                <flux:dropdown>
                    <flux:button icon-trailing="chevron-up-down" size="sm" variant="subtle" class="flex justify-between">
                        {{ Auth::user()->currentTeam->name }}
                    </flux:button>
                    <flux:menu>
                        <flux:menu.group heading="Team Management">
                            <flux:menu.item icon="cog-6-tooth" href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                {{ __('Team Settings') }}
                            </flux:menu.item>
                            @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                <flux:menu.item icon="plus" href="{{ route('teams.create') }}">
                                {{ __('Create New Team') }}
                            </flux:menu.item>
                            @endcan
                        </flux:menu.group>

                        <flux:menu.group heading="Switch Team">
                            @if (Auth::user()->allTeams()->count() > 1)
                            @foreach (Auth::user()->allTeams() as $team)
                                <x-switchable-team :team="$team" />
                                @endforeach
                            @endif
                        </flux:menu.group>
                    </flux:menu>
                </flux:dropdown>
            @endif

            <flux:dropdown  align="start" class="max-lg:hidden">
                <flux:profile
                    avatar="{{ Laravel\Jetstream\Jetstream::managesProfilePhotos() ? Auth::user()->profile_photo_url : '' }}"
                    name="{{ Auth::user()->name }}"
                />

                <flux:menu>
                    <flux:menu.item icon="user" href="{{ route('profile.show') }}">{{ __('Profile') }}</flux:menu.item>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:menu.item icon="arrow-right-start-on-rectangle" as="button" type="submit">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>


        </flux:sidebar>
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" alignt="start">
                <flux:profile
                    avatar="{{ Laravel\Jetstream\Jetstream::managesProfilePhotos() ? Auth::user()->profile_photo_url : '' }}"
                    name="{{ Auth::user()->name }}"
                />

                <flux:menu>
                    <flux:menu.item icon="user" href="{{ route('profile.show') }}">{{ __('Profile') }}</flux:menu.item>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:menu.item icon="arrow-right-start-on-rectangle" as="button" type="submit">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>


        </flux:header>
        <flux:main class="bg-base-100">
            {{ $slot }}
        </flux:main>
        <flux:toast />


        @stack('modals')

        @fluxScripts
        @livewireScripts
    </body>
</html>
