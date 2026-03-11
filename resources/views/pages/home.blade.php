<?php
// ⚡ home

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Home - TokoPun')] class extends Component {
    public int $count = 0;

    public function increment(): void
    {
        $this->count++;
    }

    public function decrement(): void
    {
        $this->count--;
    }
};

?>

<div>
    <div class="mx-auto max-w-4xl px-4 py-16">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-900">
                🎉 Welcome to <span class="text-indigo-600">TokoPun</span>
            </h1>
            <p class="mt-4 text-lg text-gray-600">
                Livewire is ready! This is a full-page Livewire component.
            </p>

            <div class="mt-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <p class="text-sm text-gray-500">Counter Demo</p>
                <p class="mt-2 text-5xl font-bold text-indigo-600">{{ $count }}</p>
                <div class="mt-4 flex justify-center gap-3">
                    <button wire:click="decrement"
                        class="rounded-lg bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-300">
                        − Kurangi
                    </button>
                    <button wire:click="increment"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700">
                        + Tambah
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
