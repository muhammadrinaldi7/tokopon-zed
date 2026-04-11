<div>
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Pengaturan Kurir & Pengiriman</h1>
        <p class="text-gray-500 mt-2 font-medium">Atur integrasi pengiriman otomatis menggunakan API Biteship.</p>
    </div>

    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
        <form wire:submit="save" class="space-y-6">
            
            {{-- Biteship API Key --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Biteship API Key (Secret)</label>
                <input type="password" wire:model="biteshipApiKey" placeholder="biteship_test_..."
                    class="w-full text-sm rounded-xl border-gray-200 px-4 py-3.5 focus:ring-2 focus:ring-[#4E44DB]/20 focus:border-[#4E44DB] transition">
                <p class="text-xs text-gray-400 mt-2 ml-1">Dapatkan API Key di Dashboard Biteship -> Settings -> API Keys.</p>
                @error('biteshipApiKey') <span class="text-xs text-rose-500 ml-1 mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Store Origin Postal Code --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">Kode Pos Toko (Origin)</label>
                <input type="text" wire:model="storeOriginPostalCode" placeholder="Contoh: 12345"
                    class="w-full text-sm rounded-xl border-gray-200 px-4 py-3.5 focus:ring-2 focus:ring-[#4E44DB]/20 focus:border-[#4E44DB] transition">
                <p class="text-xs text-gray-400 mt-2 ml-1">Ini adalah lokasi awal pengiriman barang Anda. Dibutuhkan oleh Biteship untuk menghitung jarak & ongkir ke pembeli.</p>
                @error('storeOriginPostalCode') <span class="text-xs text-rose-500 ml-1 mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Active Couriers Checkboxes --}}
            <div class="pt-4 border-t border-gray-100">
                <label class="block text-sm font-bold text-gray-700 mb-3 ml-1">Kurir Aktif (Ekspedisi)</label>
                <p class="text-xs text-gray-500 mb-4 ml-1">Pilih kurir apa saja yang ingin Anda tawarkan saat pelanggan melakukan checkout.</p>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @foreach ($availableCouriers as $code => $label)
                        <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors {{ in_array($code, $biteshipCouriers) ? 'bg-[#4E44DB]/5 border-[#4E44DB]' : '' }}">
                            <input type="checkbox" wire:model.live="biteshipCouriers" value="{{ $code }}"
                                class="mt-0.5 w-4 h-4 text-[#4E44DB] border-gray-300 rounded focus:ring-[#4E44DB]">
                            <span class="text-xs font-semibold text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Actions --}}
            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit"
                    class="bg-[#4E44DB] text-white px-8 py-3.5 rounded-xl font-bold hover:bg-[#3f36b8] transition shadow-lg shadow-[#4E44DB]/25 flex items-center gap-2"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Simpan Pengaturan</span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
