<div class="bg-gray-50 min-h-screen pb-20">
    <div class="max-w-7xl mx-auto px-6 pt-8">
        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('cart') }}" wire:navigate
                class="text-sm font-medium text-gray-400 hover:text-[#4E44DB] transition">← Kembali ke Keranjang</a>
            <h1 class="text-3xl font-extrabold text-gray-900 mt-2">Checkout</h1>
        </div>

        @if ($items->isEmpty())
            <div class="bg-white rounded-3xl p-16 text-center shadow-sm border border-gray-100">
                <p class="text-gray-500 font-medium text-lg">Keranjang belanja kosong.</p>
                <a href="{{ route('products.index') }}" wire:navigate
                    class="inline-block mt-4 bg-[#4E44DB] text-white px-6 py-3 rounded-xl font-bold hover:bg-[#3f36b8] transition">
                    Belanja Sekarang
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- LEFT: Address & Items --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Shipping Address --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h2 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#4E44DB]" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Alamat Pengiriman
                        </h2>

                        {{-- Saved Addresses --}}
                        @if (count($savedAddresses) > 0)
                            <div class="space-y-3 mb-4">
                                @foreach ($savedAddresses as $addr)
                                    <button wire:click="selectAddress({{ $addr['id'] }})"
                                        @class([
                                            'w-full text-left p-4 rounded-xl border-2 transition-all',
                                            'border-[#4E44DB] bg-[#4E44DB]/5' =>
                                                $selectedAddressId === $addr['id'],
                                            'border-gray-200 hover:border-gray-300' =>
                                                $selectedAddressId !== $addr['id'],
                                        ])>
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span
                                                        class="font-bold text-gray-800">{{ $addr['recipient_name'] }}</span>
                                                    @if ($addr['is_primary'])
                                                        <span
                                                            class="text-[10px] font-bold text-[#4E44DB] bg-[#4E44DB]/10 px-2 py-0.5 rounded-md">UTAMA</span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-gray-500">{{ $addr['phone_number'] }}</p>
                                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">
                                                    {{ $addr['full_address'] }}</p>
                                            </div>
                                            @if ($selectedAddressId === $addr['id'])
                                                <svg class="w-6 h-6 text-[#4E44DB] shrink-0" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                            <button wire:click="useNewAddress"
                                class="text-sm font-bold text-[#4E44DB] hover:underline">+ Tambah Alamat Baru</button>
                        @endif

                        {{-- Address Form --}}
                        @if ($showAddressForm || count($savedAddresses) === 0)
                            <div class="space-y-4 mt-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-600 mb-1.5 ml-1">Penerima</label>
                                        <input type="text" wire:model="recipientName"
                                            placeholder="Nama lengkap penerima"
                                            class="w-full text-sm rounded-xl border-gray-200 px-4 py-3 focus:ring-2 focus:ring-[#4E44DB]/20 focus:border-[#4E44DB] transition">
                                        @error('recipientName')
                                            <span class="text-xs text-rose-500 ml-1 mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 mb-1.5 ml-1">No.
                                            Telepon</label>
                                        <input type="text" wire:model="phoneNumber" placeholder="08xxxxxxxxxx"
                                            class="w-full text-sm rounded-xl border-gray-200 px-4 py-3 focus:ring-2 focus:ring-[#4E44DB]/20 focus:border-[#4E44DB] transition">
                                        @error('phoneNumber')
                                            <span class="text-xs text-rose-500 ml-1 mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1.5 ml-1">Alamat
                                        Lengkap</label>
                                    <textarea wire:model="fullAddress" rows="3"
                                        placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi"
                                        class="w-full text-sm rounded-xl border-gray-200 px-4 py-3 focus:ring-2 focus:ring-[#4E44DB]/20 focus:border-[#4E44DB] transition resize-none"></textarea>
                                    @error('fullAddress')
                                        <span class="text-xs text-rose-500 ml-1 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-xs font-bold text-gray-600 mb-1.5 ml-1">Kode Pos</label>
                                    <input type="text" wire:model="postalCode" placeholder="12345"
                                        class="w-full text-sm rounded-xl border-gray-200 px-4 py-3 focus:ring-2 focus:ring-[#4E44DB]/20 focus:border-[#4E44DB] transition">
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Order Items --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h2 class="font-bold text-gray-800 text-lg mb-4">Produk Dipesan ({{ $totalItems }} item)
                        </h2>
                        <div class="divide-y divide-gray-100">
                            @foreach ($items as $item)
                                @php
                                    $variant = $item->productVariant;
                                    $product = $variant->product;
                                    $imgUrl =
                                        $product->getFirstMediaUrl('cover', 'thumb') ?:
                                        $product->getFirstMediaUrl('gallery', 'thumb');
                                @endphp
                                <div class="flex gap-4 py-4 first:pt-0 last:pb-0">
                                    <div
                                        class="w-16 h-16 rounded-xl bg-gray-50 overflow-hidden border border-gray-100 shrink-0">
                                        @if ($imgUrl)
                                            <img src="{{ $imgUrl }}" alt="{{ $product->name }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div
                                                class="w-full h-full flex items-center justify-center text-gray-300">
                                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-gray-800 text-sm truncate">{{ $product->name }}
                                        </h3>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            {{ $variant->ram ? $variant->ram . ' / ' : '' }}{{ $variant->storage ?? '' }}
                                            {{ $variant->color ? '- ' . $variant->color : '' }}
                                            · {{ $variant->condition }}
                                        </p>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-xs text-gray-500">{{ $item->qty }}x</span>
                                            <span class="font-bold text-gray-800 text-sm">
                                                Rp {{ number_format($item->qty * $variant->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <label class="block text-xs font-bold text-gray-600 mb-1.5 ml-1">Catatan untuk Penjual
                            <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <textarea wire:model="notes" rows="2" placeholder="Contoh: Warna hitam, packing kayu, dll..."
                            class="w-full text-sm rounded-xl border-gray-200 px-4 py-3 focus:ring-2 focus:ring-[#4E44DB]/20 focus:border-[#4E44DB] transition resize-none"></textarea>
                    </div>
                </div>

                {{-- RIGHT: Order Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 sticky top-24">
                        <h2 class="font-bold text-gray-800 text-lg mb-4">Ringkasan Pesanan</h2>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Subtotal ({{ $totalItems }} item)</span>
                                <span class="font-semibold text-gray-800">Rp
                                    {{ number_format($totalPrice, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Ongkos Kirim</span>
                                <span class="font-semibold text-gray-500 italic text-xs">Segera tersedia</span>
                            </div>
                            <div class="border-t border-gray-100 pt-3 flex justify-between">
                                <span class="font-bold text-gray-900 text-base">Total</span>
                                <span class="font-black text-[#4E44DB] text-xl">Rp
                                    {{ number_format($totalPrice, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button wire:click="placeOrder"
                            class="w-full mt-6 bg-[#4E44DB] text-white py-4 rounded-xl font-bold text-base hover:bg-[#3f36b8] active:scale-[0.98] transition-all shadow-lg shadow-[#4E44DB]/25 flex items-center justify-center gap-2"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="placeOrder">Buat Pesanan</span>
                            <span wire:loading wire:target="placeOrder" class="flex items-center gap-2">
                                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>

                        <p class="text-[11px] text-gray-400 text-center mt-3 leading-relaxed">
                            Dengan membuat pesanan, Anda menyetujui syarat & ketentuan TokoPun.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
