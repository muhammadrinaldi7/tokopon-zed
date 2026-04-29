<div class="bg-gray-50 min-h-screen pb-20">
    {{-- Header Banner --}}
    <div class="bg-linear-to-r from-[#4E44DB] to-[#0097FF] text-white pt-10 pb-16 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-10">
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-white blur-3xl"></div>
        </div>
        <div class="max-w-5xl mx-auto px-6 relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <a href="/products" wire:navigate class="text-white/70 hover:text-white transition text-sm">
                    ← Lanjut Belanja
                </a>
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold flex items-center gap-3">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                </svg>
                Keranjang Belanja
            </h1>
            <p class="text-white/70 mt-1">
                @if ($totalItems > 0)
                    {{ $totalItems }} item di keranjang
                @else
                    Keranjang kosong
                @endif
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-6 -mt-8 relative z-20">
        @if ($items->isEmpty())
            {{-- Empty Cart State --}}
            <div class="bg-white rounded-3xl p-16 text-center shadow-xl border border-gray-100">
                <div class="w-28 h-28 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-14 h-14 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Keranjang Belanjamu Kosong</h3>
                <p class="text-gray-500 mb-8 max-w-sm mx-auto">Yuk mulai belanja dan temukan smartphone impianmu!</p>
                <a href="{{ route('buy-mobile') }}" wire:navigate
                    class="inline-flex items-center gap-2 px-8 py-3.5 text-sm font-bold text-white bg-[#4E44DB] rounded-2xl shadow-lg shadow-[#4E44DB]/30 hover:bg-[#3d35b8] hover:-translate-y-0.5 transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Cart Items --}}
                <div class="lg:col-span-2 space-y-4">
                    {{-- Header --}}
                    <div
                        class="bg-white/80 backdrop-blur-xl rounded-2xl px-6 py-4 shadow-sm border border-white flex items-center justify-between">
                        <span class="text-sm font-semibold text-gray-600">{{ $items->count() }} Produk</span>
                        <button wire:click="clearCart" wire:confirm="Hapus semua item dari keranjang?"
                            class="text-xs font-semibold text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">
                            Hapus Semua
                        </button>
                    </div>

                    {{-- Item Cards --}}
                    @foreach ($items as $item)
                        @php
                            $variant = $item->productVariant;
                            $product = $variant->product;
                            $imageUrl =
                                $product->getFirstMediaUrl('cover', 'thumb') ?:
                                $product->getFirstMediaUrl('gallery', 'thumb') ?:
                                $product->getFirstMediaUrl('cover') ?:
                                $product->getFirstMediaUrl('gallery');
                        @endphp
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow"
                            wire:key="cart-item-{{ $item->id }}">
                            <div class="flex gap-4 p-5">
                                {{-- Image --}}
                                <div class="w-24 h-24 md:w-28 md:h-28 rounded-xl bg-gray-50 overflow-hidden shrink-0">
                                    @if ($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-200" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Details --}}
                                <div class="flex-1 min-w-0 flex flex-col">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            @if ($product->brand)
                                                <span
                                                    class="text-[10px] font-bold text-[#4E44DB] uppercase tracking-wider">{{ $product->brand->name }}</span>
                                            @endif
                                            <h3
                                                class="font-bold text-gray-800 text-sm md:text-base leading-tight line-clamp-2">
                                                {{ $product->name }}
                                            </h3>
                                            <div class="flex flex-wrap gap-1.5 mt-1.5">
                                                @if ($variant->condition)
                                                    <span
                                                        class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">{{ $variant->condition }}</span>
                                                @endif
                                                @if ($variant->color)
                                                    <span
                                                        class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-blue-50 text-blue-600">{{ $variant->color }}</span>
                                                @endif
                                                @if ($variant->storage)
                                                    <span
                                                        class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-purple-50 text-purple-600">{{ $variant->storage }}</span>
                                                @endif
                                                @if ($variant->ram)
                                                    <span
                                                        class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-600">{{ $variant->ram }}
                                                        RAM</span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Remove Button --}}
                                        <button wire:click="removeItem({{ $item->id }})"
                                            class="text-gray-300 hover:text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition shrink-0">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Price + Qty --}}
                                    <div class="mt-auto pt-3 flex items-center justify-between">
                                        <p class="font-black text-gray-900 text-base md:text-lg">
                                            Rp {{ number_format($variant->price, 0, ',', '.') }}
                                        </p>

                                        {{-- Qty Controls --}}
                                        <div
                                            class="flex items-center gap-0 bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                                            <button wire:click="decrementQty({{ $item->id }})"
                                                class="w-9 h-9 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-[#4E44DB] transition disabled:opacity-30 disabled:cursor-not-allowed"
                                                @if ($item->qty <= 1) disabled @endif>
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" d="M5 12h14" />
                                                </svg>
                                            </button>
                                            <span
                                                class="w-10 text-center text-sm font-bold text-gray-800 select-none">{{ $item->qty }}</span>
                                            <button wire:click="incrementQty({{ $item->id }})"
                                                class="w-9 h-9 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-[#4E44DB] transition disabled:opacity-30 disabled:cursor-not-allowed"
                                                @if ($item->qty >= $variant->stock) disabled @endif>
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" d="M12 5v14m-7-7h14" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Stock warning --}}
                                    @if ($variant->stock <= 3 && $variant->stock > 0)
                                        <p
                                            class="text-[10px] font-semibold text-amber-500 mt-1.5 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Sisa {{ $variant->stock }} unit
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Order Summary Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-[88px]">
                        <h3 class="font-bold text-gray-800 text-lg mb-5">Ringkasan Belanja</h3>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-gray-500">
                                <span>Total Harga ({{ $totalItems }} item)</span>
                                <span class="font-semibold text-gray-700">Rp
                                    {{ number_format($totalPrice, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 mt-5 pt-5">
                            <div class="flex justify-between items-center mb-5">
                                <span class="font-bold text-gray-800">Total</span>
                                <span class="font-black text-xl text-[#4E44DB]">Rp
                                    {{ number_format($totalPrice, 0, ',', '.') }}</span>
                            </div>

                            <a href="{{ route('checkout') }}" wire:navigate
                                class="w-full py-3.5 bg-[#4E44DB] text-white font-bold rounded-2xl shadow-lg shadow-[#4E44DB]/25 hover:bg-[#3d35b8] hover:-translate-y-0.5 transition-all text-sm flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                                Checkout
                            </a>

                            <p class="text-[10px] text-gray-400 text-center mt-3">
                                Pembayaran aman & terpercaya
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
