<flux:navbar x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <flux:navbar.item href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </flux:navbar.item>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <flux:dropdown>
                        <flux:button icon-trailing="chevron-down">
                            {{ Auth::user()->currentTeam->name }}
                        </flux:button>
                        <flux:menu>
                            <flux:menu.group heading="{{ __('Manage Team') }}">
                                <flux:menu.item href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                    {{ __('Team Settings') }}
                                </flux:menu.item>
                                @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                    <flux:menu.item href="{{ route('teams.create') }}">
                                        {{ __('Create New Team') }}
                                    </flux:menu.item>
                                @endcan
                            </flux:menu.group>
                            @if (Auth::user()->allTeams()->count() > 1)
                                <flux:menu.separator />
                                <flux:menu.group heading="{{ __('Switch Teams') }}">
                                    @foreach (Auth::user()->allTeams() as $team)
                                        <x-switchable-team :team="$team" />
                                    @endforeach
                                </flux:menu.group>
                            @endif
                        </flux:menu>
                    </flux:dropdown>
                @endif

                <!-- Settings Dropdown -->
                <flux:dropdown>
                    <flux:button icon-trailing="chevron-down">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        @else
                            {{ Auth::user()->name }}
                        @endif
                    </flux:button>
                    <flux:menu>
                        <flux:menu.group heading="{{ __('Manage Account') }}">
                            <flux:menu.item href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </flux:menu.item>
                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <flux:menu.item href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </flux:menu.item>
                            @endif
                        </flux:menu.group>
                        <flux:menu.separator />
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <flux:menu.item href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <flux:button @click="open = !open" icon="bars-3" variant="ghost" />
            </div>
        </div>
    </div>

    <!-- ... Responsive Navigation Menu (can be similarly updated) ... -->
</flux:navbar>