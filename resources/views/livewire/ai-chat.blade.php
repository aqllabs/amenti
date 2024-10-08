<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Services\OpenAIService;

new #[Layout('layouts.app')] class extends Component
{
    public $messages = [];
    public $userInput = '';

    public function mount()
    {
        $this->messages[] = [
            'role' => 'system',
            'content' => 'You are a helpful AI assistant.'
        ];
    }

    public function sendMessage(OpenAIService $openAIService)
    {
        if (empty($this->userInput)) {
            return;
        }

        $this->messages[] = [
            'role' => 'user',
            'content' => $this->userInput
        ];

        $response = $openAIService->completion($this->userInput);

        $this->messages[] = [
            'role' => 'assistant',
            'content' => $response['choices'][0]['message']['content']
        ];

        $this->userInput = '';
    }
}; ?>

<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Personal Mentor</h1>

    <div class="bg-white shadow-md rounded-lg p-4 mb-4 h-96 overflow-y-auto">
        @foreach ($messages as $message)
            @if ($message['role'] !== 'system')
                <flux:card class="mb-4 {{ $message['role'] === 'user' ? 'text-right' : 'text-left' }}">
                    <span class="inline-block p-2 rounded-lg {{ $message['role'] === 'user' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                        {{ $message['content'] }}
                    </span>
                </flux:card>
            @endif
        @endforeach
    </div>

    <form wire:submit.prevent="sendMessage" class="flex space-x-2">
        <flux:input type="text" wire:model="userInput" class="" placeholder="Type your message..." />
        <flux:button type="submit" class="">Send</flux:button>
    </form>
</div>
