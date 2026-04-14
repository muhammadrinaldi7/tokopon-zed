<?php
// ⚡ header

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public bool $mobileMenuOpen = false;
    public int $cartCount = 0;

    public function mount(): void
    {
        $this->refreshCartCount();
    }

    #[On('cart-updated')]
    public function refreshCartCount(): void
    {
        $this->cartCount = app(CartService::class)->getCount();
    }

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
            <a href="/buy-mobile" wire:navigate
                class="px-4 py-2 text-sm font-medium rounded-lg transition {{ request()->is('products*') ? 'text-blue-500 bg-blue-50 font-semibold' : 'text-gray-500 hover:text-blue-500 hover:bg-blue-50/50' }}">
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
            {{-- Cart Icon --}}
            <a href="/cart" wire:navigate
                class="relative p-2 text-gray-500 hover:text-[#4E44DB] hover:bg-blue-50 rounded-xl transition {{ request()->is('cart') ? 'text-[#4E44DB] bg-blue-50' : '' }}">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                </svg>
                @if ($cartCount > 0)
                    <span
                        class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm animate-[bounce_0.5s_ease-in-out]">
                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                    </span>
                @endif
            </a>

            @auth
                <span class="text-sm text-gray-600">Halo, <strong>{{ auth()->user()->name }}</strong></span>
                <a href="{{ route('orders.index') }}" wire:navigate
                    class="px-4 py-2 text-sm font-semibold text-gray-500 hover:text-[#4E44DB] hover:bg-blue-50 rounded-lg transition {{ request()->is('orders*') ? 'text-[#4E44DB] bg-blue-50' : '' }}">
                    Pesanan
                </a>
                <a href="{{ route('trade-ins.index') }}" wire:navigate
                    class="px-4 py-2 text-sm font-semibold text-gray-500 hover:text-[#4E44DB] hover:bg-amber-50 rounded-lg transition {{ request()->routeIs('trade-ins.*') ? 'text-amber-600 bg-amber-50' : '' }}">
                    Tukar Tambah
                </a>
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

        {{-- Mobile Right Actions --}}
        <div class="flex lg:hidden items-center gap-2">
            {{-- Mobile Cart Icon --}}
            <a href="/cart" wire:navigate class="relative p-2 text-gray-500 hover:text-[#4E44DB] transition">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                </svg>
                @if ($cartCount > 0)
                    <span
                        class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm">
                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                    </span>
                @endif
            </a>

            {{-- Hamburger --}}
            <button class="flex flex-col gap-[5px] p-2" wire:click="toggleMobileMenu" aria-label="Toggle menu">
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
    </div>

    {{-- Mobile Menu --}}
    @if ($mobileMenuOpen)
        <div class="lg:hidden flex flex-col px-6 pb-6 border-t border-gray-100 bg-white">
            <a href="/" wire:navigate
                class="block px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->is('/') ? 'text-blue-500 bg-blue-50' : 'text-gray-500 hover:text-blue-500 hover:bg-blue-50/50' }}">
                Home
            </a>
            <a href="/products" wire:navigate
                class="block px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->is('products*') ? 'text-blue-500 bg-blue-50 font-semibold' : 'text-gray-500 hover:text-blue-500 hover:bg-blue-50/50' }}">
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

            {{-- Mobile Cart Link --}}
            <a href="/cart" wire:navigate
                class="flex items-center gap-2 px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->is('cart') ? 'text-blue-500 bg-blue-50 font-semibold' : 'text-gray-500 hover:text-blue-500 hover:bg-blue-50/50' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                </svg>
                Keranjang
                @if ($cartCount > 0)
                    <span
                        class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $cartCount }}</span>
                @endif
            </a>

            <div class="flex flex-col gap-3 mt-4 pt-4 border-t border-gray-100">
                @auth
                    <span class="px-4 text-sm text-gray-600">Halo, <strong>{{ auth()->user()->name }}</strong></span>
                    <a href="{{ route('orders.index') }}" wire:navigate
                        class="flex items-center gap-2 px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->is('orders*') ? 'text-blue-500 bg-blue-50 font-semibold' : 'text-gray-500 hover:text-blue-500 hover:bg-blue-50/50' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Pesanan Saya
                    </a>
                    <a href="{{ route('trade-ins.index') }}" wire:navigate
                        class="flex items-center gap-2 px-4 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('trade-ins.*') ? 'text-amber-500 bg-amber-50 font-semibold' : 'text-gray-500 hover:text-amber-500 hover:bg-amber-50/50' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Tukar Tambah
                    </a>
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
