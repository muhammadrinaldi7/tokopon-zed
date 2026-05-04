<div class="fixed top-5 left-1/2 -translate-x-1/2 z-99 flex flex-col gap-3 w-full max-w-sm px-4 pointer-events-none">

    <!-- CSS murni untuk animasi masuk -->
    <style>
        @keyframes slideDownIos {
            0% {
                opacity: 0;
                transform: translateY(-20px) scale(0.9);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-ios-toast {
            animation: slideDownIos 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>

    @foreach ($toasts as $toast)
        <!-- Tambahkan wire:key agar Livewire tidak bingung saat render ulang -->
        <div wire:key="{{ $toast['id'] }}"
            class="animate-ios-toast pointer-events-auto flex items-center gap-3 p-4 bg-white/50 dark:bg-[#1c1c1e]/50 backdrop-blur-xl backdrop-saturate-150 border border-white/40 dark:border-white/10 shadow-[0_8px_30px_rgb(0,0,0,0.12)] rounded-[20px] text-[15px] font-medium text-gray-800 dark:text-gray-100">
            <!-- Icon Success -->
            @if ($toast['type'] === 'success')
                <div
                    class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-green-500/10 text-green-500 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            @endif

            <!-- Icon Error -->
            @if ($toast['type'] === 'error')
                <div
                    class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-red-500/10 text-red-500 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            @endif

            <!-- Pesan -->
            <div class="flex-1 tracking-tight">
                {{ $toast['message'] }}
            </div>

            <!-- Tombol Close manual memanggil method PHP -->
            <button wire:click="removeToast('{{ $toast['id'] }}')"
                class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors focus:outline-none p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    @endforeach
</div>
