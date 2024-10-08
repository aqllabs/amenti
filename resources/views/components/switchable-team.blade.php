@props(['team', 'component' => 'dropdown-link'])

<form method="POST" action="{{ route('current-team.update') }}" x-data>
    @method('PUT')
    @csrf

    <!-- Hidden Team ID -->
    <input type="hidden" name="team_id" value="{{ $team->id }}">

        <flux:menu.item icon="{{
        Auth::user()->isCurrentTeam($team) ? 'check-circle' : ''
        }}" class="flex items-center" href="#" x-on:click.prevent="$root.submit();">
            <div class="truncate">{{ $team->name }}</div>
    </flux:menu.item>
</form>
