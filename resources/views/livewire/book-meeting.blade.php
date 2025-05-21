<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')]
class extends Component {}; ?>


<div>
    <div class="flex justify-center items-center h-screen">
        <div class="w-full max-w-md">
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h2 class="text-2xl font-bold mb-4">Book a Meeting</h2>
                <form wire:submit="bookMeeting">
                    <div class="mb-4">//</div>
                </form>
            </div>
        </div>
    </div>
</div>
