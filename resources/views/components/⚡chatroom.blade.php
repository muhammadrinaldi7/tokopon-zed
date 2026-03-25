<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Message;
use App\Models\Conversation;
use App\Events\MessageSent;

new class extends Component {
    public string $message = '';
    public array $messages = [];
    public ?int $conversationId = null;
    public string $status = 'open';

    public function mount()
    {
        $user = auth()->user();

        // Cari atau buat conversation milik user
        $conversation = Conversation::firstOrCreate(
            ['user_id' => $user->id],
            ['status' => 'open']
        );

        $this->conversationId = $conversation->id;
        $this->status = $conversation->status;

        // Load 50 pesan terakhir
        $dbMessages = Message::with('user')
            ->where('conversation_id', $conversation->id)
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        $this->messages = $dbMessages->map(fn($msg) => [
            'user' => $msg->user->name,
            'text' => $msg->message,
            'time' => $msg->created_at->format('H:i'),
            'userId' => $msg->user_id,
            'isCs' => $msg->user->hasRole('cs'),
        ])->toArray();
    }

    public function sendMessage()
    {
        if (trim($this->message) === '') return;

        $user = auth()->user();

        // Pastikan conversation masih open
        $conversation = Conversation::find($this->conversationId);
        if (!$conversation || $conversation->status === 'closed') {
            $this->dispatch('notify', type: 'error', message: 'Percakapan sudah ditutup.');
            return;
        }

        Message::create([
            'conversation_id' => $this->conversationId,
            'user_id' => $user->id,
            'message' => $this->message,
        ]);

        $this->messages[] = [
            'user' => $user->name,
            'text' => $this->message,
            'time' => now()->format('H:i'),
            'userId' => $user->id,
            'isCs' => false,
        ];

        broadcast(new MessageSent(
            user: $user->name,
            message: $this->message,
            time: now()->format('H:i'),
            userId: $user->id,
            conversationId: $this->conversationId,
        ))->toOthers();

        $this->message = '';
        $this->dispatch('message-sent');
    }

    // Polling untuk memastikan pesan CS masuk meskipun tanpa Reverb
    public function refreshMessages()
    {
        $oldCount = count($this->messages);

        $dbMessages = Message::with('user')
            ->where('conversation_id', $this->conversationId)
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        $this->messages = $dbMessages->map(fn($msg) => [
            'user' => $msg->user->name,
            'text' => $msg->message,
            'time' => $msg->created_at->format('H:i'),
            'userId' => $msg->user_id,
            'isCs' => $msg->user->hasRole('cs'),
        ])->toArray();

        // Update status juga saat refresh
        $this->status = Conversation::where('id', $this->conversationId)->value('status') ?? 'open';

        // Auto-scroll jika ada pesan baru
        if (count($this->messages) > $oldCount) {
            $this->dispatch('message-sent');
        }
    }

    #[On('echo-private:conversation.{conversationId},MessageSent')]
    public function listenForMessage($event)
    {
        $this->refreshMessages();
    }

    public function requestReopen()
    {
        $user = auth()->user();
        $conversation = Conversation::find($this->conversationId);

        if (!$conversation) return;

        // Update status jadi open
        $conversation->update(['status' => 'open']);
        $this->status = 'open';

        // Kirim pesan otomatis
        $msgText = '--- User meminta untuk membuka kembali percakapan ---';
        Message::create([
            'conversation_id' => $this->conversationId,
            'user_id' => $user->id,
            'message' => $msgText,
        ]);

        broadcast(new MessageSent(
            user: $user->name,
            message: $msgText,
            time: now()->format('H:i'),
            userId: $user->id,
            conversationId: $this->conversationId,
        ))->toOthers();

        $this->refreshMessages();
    }
};
?>

{{-- Floating CS Chat Widget --}}
<div
    x-data="{ open: false }"
    wire:poll.3s="refreshMessages"
    class="fixed bottom-6 right-6 z-50"
    style="font-family: 'Inter', sans-serif;"
>
    {{-- Toggle Button --}}
    <button
        @click="open = !open"
        class="w-14 h-14 rounded-full shadow-xl flex items-center justify-center transition-all duration-300 hover:scale-110"
        :class="open ? 'bg-red-500 hover:bg-red-600' : 'bg-gradient-to-br from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700'"
        style="box-shadow: 0 8px 32px rgba(16, 185, 129, 0.4);"
    >
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    {{-- Chat Panel --}}
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="absolute bottom-20 right-0 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100"
        style="box-shadow: 0 20px 60px rgba(0,0,0,0.15); max-height: 520px;"
    >
        {{-- Header --}}
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <p class="font-bold text-sm">Customer Service</p>
                <div class="flex items-center gap-1.5">
                    <div class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></div>
                    <p class="text-xs text-emerald-100">Online • Siap membantu</p>
                </div>
            </div>
        </div>

        {{-- Messages --}}
        <div
            x-ref="chatContainer"
            x-init="$nextTick(() => $refs.chatContainer.scrollTop = $refs.chatContainer.scrollHeight)"
            @message-sent.window="$nextTick(() => $refs.chatContainer.scrollTop = $refs.chatContainer.scrollHeight)"
            class="p-4 overflow-y-auto bg-gray-50 flex flex-col gap-3"
            style="height: 320px;"
        >
            @forelse($messages as $msg)
                @php $isMine = $msg['userId'] === auth()->id(); @endphp
                <div class="flex flex-col {{ $isMine ? 'items-end' : 'items-start' }}">
                    <div class="px-3 py-2 rounded-2xl max-w-[80%] text-sm
                        {{ $isMine
                            ? 'bg-gradient-to-br from-emerald-500 to-teal-600 text-white rounded-br-md'
                            : 'bg-white text-gray-800 border border-gray-200 rounded-bl-md shadow-sm' }}">
                        @unless($isMine)
                            <span class="block text-xs font-semibold text-emerald-600 mb-1">
                                🎧 {{ $msg['user'] }}
                            </span>
                        @endunless
                        <span>{{ $msg['text'] }}</span>
                    </div>
                    <span class="text-[10px] text-gray-400 mt-1 px-1">{{ $msg['time'] }}</span>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-2 text-emerald-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <p class="text-sm font-medium">Halo! 👋</p>
                    <p class="text-xs text-center mt-1">Ada yang bisa kami bantu?<br>Ketik pesan Anda di bawah.</p>
                </div>
            @endforelse
        </div>

        {{-- Input Area / Closed State --}}
        <div class="p-3 bg-white border-t border-gray-100 text-center">
            @if($status === 'open')
                <form wire:submit="sendMessage" class="flex gap-2 items-center">
                    <input
                        type="text"
                        wire:model="message"
                        placeholder="Ketik pesan..."
                        class="flex-1 border border-gray-200 rounded-full px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent bg-gray-50 transition-all"
                        autocomplete="off"
                    >
                    <button
                        type="submit"
                        class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 text-white rounded-full flex items-center justify-center hover:from-emerald-600 hover:to-teal-700 transition-all duration-200 hover:scale-105 shadow-md"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" />
                        </svg>
            @if($status === 'closed')
                <div class="absolute inset-0 bg-white/50 backdrop-blur-[2px] z-10 rounded-b-xl flex flex-col items-center justify-center p-4 text-center">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-800 mb-4">Percakapan ini telah ditutup oleh CS.</p>
                    <button wire:click="requestReopen" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-xl shadow-md transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Minta Buka Chat
                    </button>
                </div>
            @endif

            <form wire:submit="sendMessage" class="relative group">
                <input wire:model="message" type="text" placeholder="Ketik pesan..."
                    class="w-full border border-gray-200 rounded-full px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#00bfa5]/20 focus:border-[#00bfa5] transition"
                    autocomplete="off" {{ $status === 'closed' ? 'disabled' : '' }}>
                <button type="submit"
                    class="absolute right-2 top-1.5 p-1.5 bg-[#00bfa5] text-white rounded-full hover:bg-[#00a68f] transition shadow-sm group-focus-within:bg-linear-to-br from-[#00bfa5] to-[#009688]">  </button>
            </form>
        </div>
    </div>
</div>
