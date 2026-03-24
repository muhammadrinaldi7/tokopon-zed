<?php
// ⚡ header

use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public bool $mobileMenuOpen = false;

    public function toggleMobileMenu(): void
    {
        $this->mobileMenuOpen = !$this->mobileMenuOpen;
    }

    public function confirmLogout(): void
    {
        $this->dispatch('show-confirm', title: 'Logout', message: 'Apakah Anda yakin ingin keluar dari akun?', confirmEvent: 'do-logout', type: 'warning', confirmText: 'Ya, Logout', cancelText: 'Batal');
    }

    #[On('do-logout')]
    public function logout(): void
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $this->redirect('/', navigate: true);
    }
};

?>

<nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 h-[72px] flex items-center justify-between">
        {{-- Logo --}}
        <a href="/" wire:navigate>
            <span
                class="text-2xl font-bold bg-linear-to-r from-[#0097FF] via-[#4E44DB] to-[#013559] bg-clip-text text-transparent">Tokopon</span>
        </a>

        {{-- Desktop Navigation --}}
        <div class="hidden lg:flex items-center gap-1">
            <a href="/" wire:navigate
                class="px-4 py-2 text-sm font-medium rounded-lg transition {{ request()->is('/') ? 'text-blue-500 bg-blue-50 font-semibold' : 'text-gray-500 hover:text-blue-500 hover:bg-blue-50/50' }}">
                Home
            </a>
            <a href="#"
                class="px-4 py-2 text-sm font-medium text-gray-500 rounded-lg transition hover:text-blue-500 hover:bg-blue-50/50">
                Buy Phones
            </a>
            <a href="#"
                class="px-4 py-2 text-sm font-medium text-gray-500 rounded-lg transition hover:text-blue-500 hover:bg-blue-50/50">
                Repair Service
            </a>
            <a href="#"
                class="px-4 py-2 text-sm font-medium text-gray-500 rounded-lg transition hover:text-blue-500 hover:bg-blue-50/50">
                Trade In
            </a>
            <a href="#"
                class="px-4 py-2 text-sm font-medium text-gray-500 rounded-lg transition hover:text-blue-500 hover:bg-blue-50/50">
                Sell Phones
            </a>
        </div>

        {{-- Desktop CTA --}}
        <div class="hidden lg:flex items-center gap-3">
            @auth
                <span class="text-sm text-gray-600">Halo, <strong>{{ auth()->user()->name }}</strong></span>
                <button wire:click="confirmLogout"
                    class="px-5 py-2 text-sm font-semibold text-gray-500 bg-gray-100 border border-gray-200 rounded-lg transition hover:bg-gray-200">
                    Logout
                </button>
            @else
                <a href="/login" wire:navigate
                    class="px-5 py-2 text-sm font-semibold text-white bg-blue-500 rounded-lg shadow-md shadow-blue-500/30 transition hover:bg-blue-600 hover:-translate-y-0.5">
                    Login
                </a>
                <a href="#"
                    class="flex items-center gap-1.5 px-5 py-2 text-sm font-semibold text-gray-500 bg-gray-100 border border-gray-200 rounded-lg transition hover:bg-gray-200 hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    Chat
                </a>
            @endauth
        </div>

        {{-- Mobile Hamburger --}}
        <button class="flex lg:hidden flex-col gap-[5px] p-2" wire:click="toggleMobileMenu" aria-label="Toggle menu">
            @if ($mobileMenuOpen)
                <span
                    class="block w-6 h-0.5 bg-gray-700 rounded translate-y-[7.5px] rotate-45 transition-all duration-300"></span>
                <span
                    class="block w-6 h-0.5 bg-gray-700 rounded opacity-0 scale-x-0 transition-all duration-300"></span>
                <span
                    class="block w-6 h-0.5 bg-gray-700 rounded -translate-y-[7.5px] -rotate-45 transition-all duration-300"></span>
            @else
                <span class="block w-6 h-0.5 bg-gray-700 rounded transition-all duration-300"></span>
                <span class="block w-6 h-0.5 bg-gray-700 rounded transition-all duration-300"></span>
                <span class="block w-6 h-0.5 bg-gray-700 rounded transition-all duration-300"></span>
            @endif
        </button>
    </div>

    {{-- Mobile Menu --}}
    @if ($mobileMenuOpen)
        <div class="lg:hidden flex flex-col px-6 pb-6 border-t border-gray-100 bg-white">
            <a href="/" wire:navigate
                class="block px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->is('/') ? 'text-blue-500 bg-blue-50' : 'text-gray-500 hover:text-blue-500 hover:bg-blue-50/50' }}">
                Home
            </a>
            <a href="#"
                class="block px-4 py-3 text-sm font-medium text-gray-500 rounded-lg transition hover:text-blue-500 hover:bg-blue-50/50">
                Buy Phones
            </a>
            <a href="#"
                class="block px-4 py-3 text-sm font-medium text-gray-500 rounded-lg transition hover:text-blue-500 hover:bg-blue-50/50">
                Repair Service
            </a>
            <a href="#"
                class="block px-4 py-3 text-sm font-medium text-gray-500 rounded-lg transition hover:text-blue-500 hover:bg-blue-50/50">
                Trade In
            </a>
            <a href="#"
                class="block px-4 py-3 text-sm font-medium text-gray-500 rounded-lg transition hover:text-blue-500 hover:bg-blue-50/50">
                Sell Phones
            </a>

            <div class="flex flex-col gap-3 mt-4 pt-4 border-t border-gray-100">
                @auth
                    <span class="px-4 text-sm text-gray-600">Halo, <strong>{{ auth()->user()->name }}</strong></span>
                    <button wire:click="confirmLogout"
                        class="block text-center px-5 py-2.5 text-sm font-semibold text-gray-500 bg-gray-100 border border-gray-200 rounded-lg transition hover:bg-gray-200">
                        Logout
                    </button>
                @else
                    <a href="/login" wire:navigate
                        class="block text-center px-5 py-2.5 text-sm font-semibold text-white bg-blue-500 rounded-lg shadow-md shadow-blue-500/30 transition hover:bg-blue-600">
                        Login
                    </a>
                    <a href="#"
                        class="flex items-center justify-center gap-1.5 px-5 py-2.5 text-sm font-semibold text-gray-500 bg-gray-100 border border-gray-200 rounded-lg transition hover:bg-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        Chat
                    </a>
                @endauth
            </div>
        </div>
    @endif
</nav>
