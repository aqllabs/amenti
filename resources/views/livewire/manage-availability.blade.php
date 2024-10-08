<div>
    <h2 class="text-2xl font-bold mb-4">Manage Availability</h2>

    <div class="mb-4">
        <label for="date" class="block mb-2">Date:</label>
        <input type="date" wire:model="date" id="date" class="w-full p-2 border rounded" wire:change="loadAvailabilities">
    </div>

    <form wire:submit.prevent="saveAvailability" class="mb-8">
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="startTime" class="block mb-2">Start Time:</label>
                <input type="time" wire:model="startTime" id="startTime" class="w-full p-2 border rounded">
            </div>
            <div>
                <label for="endTime" class="block mb-2">End Time:</label>
                <input type="time" wire:model="endTime" id="endTime" class="w-full p-2 border rounded">
            </div>
        </div>
        <div class="mb-4">
            <label for="notes" class="block mb-2">Notes:</label>
            <textarea wire:model="notes" id="notes" class="w-full p-2 border rounded"></textarea>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Availability</button>
    </form>

    <h3 class="text-xl font-bold mb-4">Availabilities for {{ $date }}</h3>
    @if($availabilities->isEmpty())
        <p>No availabilities set for this date.</p>
    @else
        <ul>
            @foreach($availabilities as $availability)
                <li class="mb-2 p-2 border rounded">
                    {{ $availability->start_time->format('H:i') }} - {{ $availability->end_time->format('H:i') }}
                    @if($availability->notes)
                        <br><small>{{ $availability->notes }}</small>
                    @endif
                    <button wire:click="deleteAvailability({{ $availability->id }})" class="text-red-500 ml-2">Delete</button>
                </li>
            @endforeach
        </ul>
    @endif
</div>