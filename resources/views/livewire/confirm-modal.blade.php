<div>
    @if ($show)
        {{-- Overlay --}}
        <div class="fixed inset-0 z-100 flex items-center justify-center p-4">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="cancel"></div>

            {{-- Modal --}}
            <div class="relative z-101 w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
                {{-- Icon --}}
                <div class="flex justify-center mb-4">
                    @if ($type === 'danger')
                        <div class="flex items-center justify-center w-14 h-14 rounded-full bg-red-100">
                            <svg class="w-7 h-7 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                        </div>
                    @elseif ($type === 'warning')
                        <div class="flex items-center justify-center w-14 h-14 rounded-full bg-amber-100">
                            <svg class="w-7 h-7 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                        </div>
                    @elseif ($type === 'success')
                        <div class="flex items-center justify-center w-14 h-14 rounded-full bg-green-100">
                            <svg class="w-7 h-7 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                    @else
                        {{-- info --}}
                        <div class="flex items-center justify-center w-14 h-14 rounded-full bg-blue-100">
                            <svg class="w-7 h-7 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Title --}}
                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">{{ $title }}</h3>

                {{-- Message --}}
                <p class="text-sm text-gray-500 text-center mb-6 leading-relaxed">{{ $message }}</p>

                {{-- Buttons --}}
                <div class="flex gap-3">
                    @if ($cancelText)
                        <button wire:click="cancel"
                            class="flex-1 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl border border-gray-200 transition hover:bg-gray-200">
                            {{ $cancelText }}
                        </button>
                    @endif

                    @php
                        $btnClass = match ($type) {
                            'danger' => 'bg-red-500 shadow-red-500/30 hover:bg-red-600',
                            'warning' => 'bg-amber-500 shadow-amber-500/30 hover:bg-amber-600',
                            'success' => 'bg-green-500 shadow-green-500/30 hover:bg-green-600',
                            default => 'bg-blue-500 shadow-blue-500/30 hover:bg-blue-600',
                        };
                    @endphp

                    <button wire:click="confirm"
                        class="flex-1 py-2.5 text-sm font-semibold text-white rounded-xl shadow-md transition {{ $btnClass }}">
                        {{ $confirmText }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
